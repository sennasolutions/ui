@php
/**
 * @name Filter Select
 * @description A filterable select using alpine
 */
@endphp

@props([
    /**
     * @param array items Items can be provided as an array (key/value) via this attribute or by using the senna.input.filter-select-item tags in the slot.
     */
    'items' => null,
    /**
     * @param bool showFilter Show the filter input. Default: true
     */
    'showFilter' => true,
    /**
     * @param string placeholder Placeholder value for the filter
     */
    'placeholder' => 'Filter..',
    /**
     * @param array val The default value if wire:model is not used
     */
    'val' => '',
    /**
     * @param string size 'xl', 'lg' or 'sm'
     */
     'size' => 'lg',
     /**
     * @param string Whether to show an error border on the input
     */
     'error' => false
])

<div
    x-data='createFilterSelect(@safe_entangle($attributes->wire('model')))'
    x-init='init()'
    {{ $attributes->merge(['class' => 'relative flex flex-col p-3 border border-gray-200 rounded-md shadow-sm']) }}
    >
    @if($showFilter)
    <input class="{{ default_input_chrome($size, $error) }}" x-ref="search" x-model="search" type="text" placeholder="{{ $placeholder }}">
    @endif
    <div class="flex w-full mt-2 opacity-50 text-sm">
        <button type="button" x-show="Array.isArray(selected)" x-on:click.prevent="selectAll">{{ __('Select all') }}</button>
        <button type="button" class="ml-auto" x-show="Array.isArray(selected)" x-on:click.prevent="unselectAll">{{ __('Deselect all') }}</button>
    </div>

    <div x-ref="slot" class="max-h-32 overflow-y-auto w-full p-1.5 rounded">
        @if($items)
        @foreach($items as $key => $value)
            <x-senna.input.filter-select-item :key="$key" :label="$value" />
        @endforeach
        @else
            {{ $slot }}
        @endif
    </div>
</div>

@once
    @push('senna-ui-scripts')
    <script>
        function createFilterSelect(selected) {
            return {
                search: '',
                visible: [],
                selected: selected,
                allNames: ['bike', 'car', 'boat'],
                selectAll() { this.selected = this.getItems() },
                unselectAll() { this.selected = []},
                init() {
                    this.$watch('search', search => {
                        this.filter()
                    })
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
