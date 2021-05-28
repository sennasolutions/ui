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
     * @param string addPlaceholder Placeholder value for the add field
     */
    'addPlaceholder' => __('Enter a value to add'),
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
])

@php
    $wireId = isset($_instance) && $_instance->id ? '"' .$_instance->id . '"' : 'null';
@endphp


<div
    x-data="createFilterSelect(@safe_entangle($attributes->wire("model")))"
    x-init='init($dispatch, {{ $wireId }}, @json($identifier))'
    {{ $attributes->merge(['class' => 'relative flex flex-col p-3 border border-gray-200 rounded-md shadow-sm']) }}
    >
    @if($showFilter)
    <input class="{{ default_input_chrome($size, $error) }}" x-ref="search" x-model="search" type="text" placeholder="{{ $placeholder }}">
    @endif
    @if($showButtons)
    <div class="flex w-full mt-2 opacity-50 text-sm">
        <button type="button" x-show="Array.isArray(selected)" x-on:click.prevent="selectAll">{{ __('Select all') }}</button>
        <button type="button" class="ml-auto" x-show="Array.isArray(selected)" x-on:click.prevent="unselectAll">{{ __('Deselect all') }}</button>
    </div>
    @endif

    <div x-ref="slot" class="max-h-32 overflow-y-auto w-full p-1.5 rounded">
        @if($items)
        @foreach($items as $key => $value)
            <x-senna.input.filter-select-item :key="$key" :label="$value" />
        @endforeach
        @else
            {{ $slot }}
        @endif
    </div>

    @if($showAddOption)
    <div class="flex space-x-2 mt-3">
        <input class="{{ default_input_chrome($size, $error) }}" x-ref="add" x-model="add" type="text" placeholder="{{ $addPlaceholder }}">
        <x-senna.button x-on:click="addValue" class="flex-shrink-0" type="submit">
            {{ __("Add") }}
        </x-senna.button>
    </div>
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
                selectAll() { this.selected = this.getItems() },
                unselectAll() { this.selected = []},
                init($dispatch, wireId, identifier) {
                    this.$dispatch = $dispatch
                    this.identifier = identifier
                    this.identifierEvent = identifier ? indentifier + ":" : "";
                    this.$wire = (window.Livewire) ? window.Livewire.find(wireId) : null;

                    this.$watch('search', search => {
                        this.filter()
                    })
                },
                // @event livewire wire:filter-select:addValue  When the add button is clicked. Has the value as parameter.
                // @event js filter-select:addValue  When the add button is clicked. Has the value as parameter.
                addValue() {
                    if (this.$wire) {
                        this.$wire.emit('filter-select:' + this.identifierEvent + 'addValue', this.add)
                    }
                    this.$dispatch('filter-select:' + this.identifierEvent + 'addValue', this.add)
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
                    return Array.from(this.$refs.slot.children)
                        .filter(x => x.textContent.toLowerCase().indexOf(this.search.toLowerCase()) >= 0)
                        // .map(x => {console.log(x.textContent.trim(), x.querySelector('[value]').value); return x})
                        .map(x => x.querySelector('[value]').value)
                }
            }
        }
    </script>
    @endpush
@endonce
