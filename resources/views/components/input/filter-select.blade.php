@php
/**
 * @name Filter Select
 * @description A filterable select using alpine.
 */
@endphp

@props([
    /**
     * @param array items Items can be provided as an array (key/value) via this attribute or by using the senna.input.filter-select-item tags in the slot.
     */
    'items' => null,
    /**
     * @param string identifier Unique string that is added onto events. Example: filter-select:{identifier}:addValue
     */
    'identifier' => '',
    /**
     * @param bool showFilter Show the filter input. Default: true
     */
    'showFilter' => true,
    /**
     * @param bool showAddOption Show a box below to manually add values
     */
    'showAddOption' => false,
    /**
     * @param bool showButtons Show the select all / deselect all buttons. Default: true
     */
    'showButtons' => true,
    /**
     * @param bool showDeleteButtons Show the delete buttons after each value
     */
    'showDeleteButtons' => false,
    /**
     * @param string placeholder Placeholder value for the filter
     */
    'placeholder' => __('Filter..'),
    /**
     * @param string noItemsText The text that is displayed when no items are in the items argument
     */
    'noItemsText' => __('No items'),
    /**
     * @param string addPlaceholder Placeholder value for the add field
     */
    'addPlaceholder' => __('Enter a value to add'),
    /**
     * @param bool showCheckRadio Show the check/radio input before the label or each item. You can disable this by setting it to false. Default: true
     */
    'showCheckRadio' => true,
    /**
     * @param array value The default value if wire:model is not used
     */
    'value' => null,
    /**
     * @param string size 'xl', 'lg' or 'sm'
     */
     'size' => 'lg',
     /**
     * @param string error Whether to show an error border on the input
     */
     'error' => false,
     /**
     * @param bool allowHtml Do not escape the label and show as html
     */
     'allowHtml' => false,

    /**
     * @param string inputClass String of classes applied to the input element
     */
    'inputClass' => '',
    'slotClass' => '',
])

@php
    $inputClass = "-inner --$size " . ($error ? '--error' : '') . ' ' . $inputClass;
    $contentAttributes = isset($content) ? $content->attributes : new Illuminate\View\ComponentAttributeBag();
@endphp

<div
    data-sn="input.filter-select"
    x-data="createFilterSelect(@safe_entangle($attributes->wire("model")))"
    x-json='@json([
        'identifier' => $identifier
    ])'
    wire:ignore.self
    wire:key="{{ $attributes->wire("model")->value() }}"
    {{ $attributes->merge(['class' => '-outer flex flex-col p-3 border space-y-2 border-gray-200 rounded-md shadow-sm'])->whereDoesntStartWith('wire:model') }}
    >
    @if($showFilter)
    <input class="{{ $inputClass }}" x-ref="search" x-model="search" type="text" placeholder="{{ $placeholder }}">
    @endif
    @if($showButtons)
    <div class="flex w-full opacity-50 text-sm">
        <button type="button" x-show="Array.isArray(selected)" x-on:click.prevent="selectAll">{{ __('Select all') }}</button>
        <button type="button" class="ml-auto" x-show="Array.isArray(selected)" x-on:click.prevent="unselectAll">{{ __('Deselect all') }}</button>
    </div>
    @endif

    <div x-ref="slot" {{ $contentAttributes->merge(['class' => "max-h-32 overflow-y-auto w-full p-1.5 rounded"]) }}>
        @if($items !== null)
            @forelse($items as $key => $value)
                @if($value['key'] ?? null && $value['label'] ?? null)
                <x-senna.input.filter-select-item :allowHtml="$allowHtml" :showCheckRadio="$showCheckRadio" :showDeleteButton="$showDeleteButtons" :key="$value['key']" :label="$value['label']" />
                @else
                <x-senna.input.filter-select-item :allowHtml="$allowHtml" :showCheckRadio="$showCheckRadio" :showDeleteButton="$showDeleteButtons" :key="$key" :label="$value" />
                @endif
            @empty
                <span>{{ $noItemsText }}</span>
            @endforelse
        @else
            {{ $content ?? $slot }}
        @endif
    </div>

    @if($showAddOption)
    <form class="flex space-x-2 mt-3" x-on:submit.prevent="addValue">
        <input class="{{ default_input_chrome($size, $error) }}" x-ref="add" x-model="add" type="text" placeholder="{{ $addPlaceholder }}">
        <x-senna.button  class="flex-shrink-0" type="submit">
            {{ __("Add") }}
        </x-senna.button>
    </form>
    @endif
</div>

@once
    @push('senna-ui-scripts')
    <script>
        function createFilterSelect(selected) {
            return {
                search: '',
                add: '',
                visible: [],
                selected: selected,
                allNames: ['bike', 'car', 'boat'],
                selectAll(search = null) { 
                    this.selected = this.getItems() 
                },
                append(search = null) { 
                    let searchBackup = this.search
                    if (search) {
                        this.search = search
                    }
                    this.selected = this.selected.concat(this.getItems())
                        .filter((item, index, self) => self.indexOf(item) === index)
                    if (search) {
                        this.search = searchBackup
                    }
                },
                unselectAll() { this.selected = this.selected.filter(s => !this.getItems().includes(s)) },
                init() {
                    console.log('init')
                    let json = JSON.parse(this.$el.getAttribute('x-json'))

                    this.identifierEvent = json.identifier ? json.indentifier + ":" : "";

                    this.$watch('search', search => {
                        this.filter()
                    })
                    this.$watch('selected', selected => {
                        console.log(selected)
                    })
                },
                // @event livewire wire:filter-select:addValue  When the add button is clicked. Has the value as parameter.
                // @event js filter-select:addValue  When the add button is clicked. Has the value as parameter.
                addValue() {
                    if (this.$wire) {
                        this.$wire.emit('filter-select:' + this.identifierEvent + 'addValue', this.add)
                    }
                    this.$dispatch('filter-select:' + this.identifierEvent + 'addValue', this.add)

                    this.add = ''
                },
                // @event livewire filter-select:removeValue  When the remove button is clicked. Has the key as parameter.
                // @event js filter-select:removeValue  When the remove button is clicked. Has the key as parameter.
                deleteValue(key) {
                    if (this.$wire) {
                        this.$wire.emit('filter-select:' + this.identifierEvent + 'deleteValue', key)
                    }
                    this.$dispatch('filter-select:' + this.identifierEvent + 'deleteValue', key)
                },
                filter() {
                    this.visible = this.getItems()
                },
                getItems() {
                    return Array.from(this.$refs.slot.querySelectorAll('input, [data-sn="input.filter-select-item"]'))
                        .filter(x => x.textContent.toLowerCase().indexOf(this.search.toLowerCase()) >= 0 ||
                                     x.title.indexOf(this.search.toLowerCase()) >= 0)
                        // .map(x => {console.log(x.textContent.trim(), x.querySelector('[value]').value); return x})
                        .map(x => x.querySelector('[value]')?.value)
                        .filter(x => x)
                }
            }
        }
    </script>
    @endpush
@endonce
