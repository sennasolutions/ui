@php
/**
 * @name Selector
 * @description A selector component select from a dropdown
 */
@endphp


@props([
    /**
     * @param null|double value The current value
     */
    'value' => null,
    /**
     * @param null|bool multiple Whether to select multiple values
     */
    'multiple' => false,
    /**
     * @param string search The search
     */
    'search' => '',
    /**
     * @param string autocomplete The endpoint to call for the options
     */
    'endpoint' => null,
    /**
     * @param null|string placeholder The placeholder
     */
    'placeholder' => "Test...",
    /**
     * @param string size 'xl', 'lg' or 'sm'
     */
    'size' => 'lg',
    /**
     * @param string error Whether to show an error border on the input
     */
    'error' => false,
    /**
     * @param array value The options for the component, value label pairs
     */
    'options' => [
        [ 'value' => 1, 'label' => 'Example' ],
        [ 'value' => 2, 'label' => 'Example 2' ]
    ],
    /**
     * @param array config Config options for this component
     */
    'config' => [
        'searchText' => __('Type to search...'),
        'noResultsText' => __('No results'),
        'loadingText' => __('Loading..'),
        'allowHtml' => false
    ]
])

@php
    $inputClass = "-inner --$size " . ($error ? '--error' : '') . " " . '';
@endphp


{{-- Checks for a wire:multiple etc --}}
@wireProps

<div 
    x-ref="container"
    wire:ignore
    data-sn="input.selector"
    {{ $attributes->namespace('container')->merge(['class' => '-outer relative ']) }}
    
    x-data="{
        id: $id('selector'),
        value: @entangleProp('value'),
        
        options: @entangleProp('options'),
        placeholder: @entangleProp('placeholder'),
        multiple: @entangleProp('multiple'),
        open: false,
        search: @entangleProp('search'),
        endpoint: @entangleProp('endpoint'),
        config: {
            searchText: 'Type to search...',
            noResultsText: 'No results',
            loadingText: 'Loading..',
            ...@entangleProp('config')
        },
        filteredOptions: [],
        isFocused: false,
        isLoading: false,
        _placeholder: null,

        init() {
            this.refreshFilteredOptions(this.endpoint ? null : this.search)

            if (this.multiple && this.value === null) {
                this.value = []
            }

            if (this.multiple && !Array.isArray(this.value)) {
                console.error('Selector: Value must be an array when multiple is true')
            }

            this.refreshPlaceholder()
            this.$watch('options', () => {
                if (this.endpoint) return;

                this.refreshFilteredOptions()
            })
            this.$watch('value', () => {
                this.refreshFilteredOptions(this.endpoint ? null : this.search)
                this.refreshPlaceholder()
            })
            this.$watch('search', () => {
                this.refreshFilteredOptions()
            })
        },
        refreshPlaceholder() {
            if (this.value === null || this.value?.length === 0) {
                this._placeholder = this.placeholder
            } else {
                this._placeholder = ''
            }
        },
        openDropdown() {
            {{-- this.$refs.container.focus() --}}
            this.open = true
            this.isFocused = true
            this.$refs.search?.focus()
        },
        closeDropdown() {
            this.open = false
            this.isFocused = false

            this.search = ''
        },
        select(option) {

            if (this.multiple) {
                this.value.push(option.value)
            } else {
                this.value = option.value

            }
            this.$refs.search?.focus()
            this.closeDropdown()
            this.search = '';
        },
        selectFirst() {
            if (this.filteredOptions.length > 0 && this.search && this.search.length > 0) {
                this.select(this.filteredOptions[0])
            }
        },
        current() {

            if (this.value === null) return []

            let values = Array.isArray(this.value) ? this.value : [this.value]
            return values.map(x => this.options.find(y => y.value == x)).filter(x => x)
        },
        focusables() {
            // All focusable element types...
            let selector = 'a, button, input:not([type=\'hidden\'], textarea, select, details, [tabindex]:not([tabindex=\'-1\'])'

            return [...this.$refs.panel.querySelectorAll(selector)]
                // All non-disabled elements...
                .filter(el => ! el.hasAttribute('disabled'))
        },
        firstFocusable() { return this.focusables()[0] },
        lastFocusable() { return this.focusables().slice(-1)[0] },
        nextFocusable() { return this.focusables()[this.nextFocusableIndex()] || this.firstFocusable() },
        prevFocusable() { return this.focusables()[this.prevFocusableIndex()] || this.lastFocusable() },
        nextFocusableIndex() { return (this.focusables().indexOf(document.activeElement) + 1) % (this.focusables().length + 1) },
        prevFocusableIndex() { return Math.max(0, this.focusables().indexOf(document.activeElement)) -1 },

        remove(selected) {
            if (this.multiple) {
                this.value = this.value.filter(x => x !== selected.value)
            } else {
                this.value = null
            }
            this.$nextTick(() => {
                this.$refs.search?.focus()
            })
        },
        backspace() {
            if (this.search && this.search.length > 0) return

            if (Array.isArray(this.value)) {
                this.value.pop()
            } else {
                this.value = null
            }
        },
        keyup($event) {
            if ($event.key.length === 1) {
                this.openDropdown()
            }
        },
        async refreshFilteredOptions(search = this.search) {
            let current = this.current()

            if (this.endpoint) {
                this.isLoading = true;
                this.filteredOptions = (await this.$wire.call(this.endpoint, search))
                this.options = this.filteredOptions.concat(current)
                this.isLoading = false;
            } else {
                this.filteredOptions = this.options
                    .filter(x => search === null || x.label.toLowerCase().indexOf(search.toLowerCase()) !== -1 && current.indexOf(x) === -1)
            }
        },
        isEmpty() {
            return this.value === null || this.value?.length === 0
        },
        statusText() {
            if (!this.search || this.search.length === 0) {
                return this.config.searchText
            }

            if (this.isLoading) {
                return this.config.loadingText
            }

            return this.config.noResultsText
        },
        tabNext($event) {
            if (!this.open || !this.search || this.search.length === 0) {
                this.open = false
                this.isFocused = false
                return;
            }
            if ($event.shiftKey) return

            this.pressDown($event)
        },
        tabPrev($event) {
            if (!this.open || !this.search || this.search.length === 0) {
                this.open = false
                this.isFocused = false
                return;
            }

            this.pressUp($event)
        },
        pressDown($event) {
            if (!this.open) this.openDropdown()

            $event.preventDefault()
            {{-- console.log(this.nextFocusable()) --}}
            this.nextFocusable()?.focus()
        },
        pressUp($event) {
            if (!this.open) this.openDropdown()
            $event.preventDefault()
            this.prevFocusable()?.focus()
        },
    }"

     x-on:keydown.escape.window="open = false"
     x-on:keydown.tab="tabNext($event)"
     x-on:keydown.shift.tab="tabPrev($event)"
     x-on:keydown.down="pressDown($event)"
     x-on:keydown.up="pressUp($event)"
     x-on:click.outside="closeDropdown"
>
    <div {{ $attributes->namespace('inner')->merge(['class' => '-inner cursor-pointer ' . $inputClass]) }} :class="{ '--focus': isFocused }" @click="openDropdown">
        {{-- Fallback select --}}
        <select {{ $attributes->root()->merge(['class' => 'w-full hidden'])->whereDoesntStartWith("wire:")->except(['id']) }} x-model="value" :multiple="multiple">
            <template x-if="placeholder">
                <option value="" class="" x-text="placeholder"></option>
            </template>
            <template x-for="option in options">
                <option x-bind:value="option.value" x-text="option.label" :selected="option.value === value">test</option>
            </template>
        </select>

        <div class="flex flex-wrap -mx-1.5 -my-1 gap-2 relative items-center">
            {{-- The selected option --}}
            <template x-for="selected in current()">
                {{-- Each label --}}
                <div :class="{ 
                    '{{ $attributes->namespace('label')->merge(['class' => 'whitespace-nowrap rounded bg-primary text-white p-1 px-1.5 text-sm flex items-center justify-center gap-1'])->get('class') }}' : multiple,
                    '{{ $attributes->namespace('label')->merge(['class' => 'whitespace-nowrap rounded px-1 flex items-center justify-center gap-1'])->get('class') }}' : !multiple,
                    
                }">
                    <span 
                        @if($config['allowHtml'] ?? false)
                            x-html="selected.label" 
                        @else
                            x-text="selected.label" 
                        @endif
                    ></span>
                    {{-- X button --}}
                    <button x-show="multiple" type="button" @click="remove(selected)">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </template> 

            {{-- Search input --}}
            <input {{ $attributes->namespace('search')->whereDoesntStartWith("wire:")->merge(['class' => '!outline-none my-1 mx-1 flex-grow w-1 !ring-0 !border-none !p-0']) }} 
                type="text" 
                x-ref="search" 
                x-model="search" 
                :disabled="!(current().length == 0 || multiple)"
                :placeholder="_placeholder"

                {{-- @focus="openDropdown"  --}}
                @click="openDropdown" 
                @keydown.backspace="backspace()" 
                @keyup="keyup" 
                @keydown.enter.prevent="selectFirst()" />

            {{-- \/ button --}}
            <svg x-show="multiple || isEmpty()" class="w-5 h-5 transition-transform" :class="{ 'rotate-180': open }" @click="openDropdown" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
            </svg>
            {{-- X button --}}
            <button x-show="!multiple && !isEmpty()" type="button" @click="remove(current()[0])">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Dropdown panel --}}
        <div
            x-ref="panel"
            x-show="open"
            x-transition.origin.top.left
            
            :id="$id('dropdown-button')"
            style="display: none;"
            class="absolute left-0 mt-2 w-full rounded-md border bg-white shadow-md max-h-36 overflow-y-auto z-20 text-black"
        >
            <template x-for="option in filteredOptions">
                <button 
                     x-on:keydown.prevent.enter="select(option)" 
                     type="button" @click.stop="select(option)"
                     @if($config['allowHtml'] ?? false)
                        x-html="option.label" 
                     @else
                        x-text="option.label" 
                     @endif
                     {{ $attributes
                            ->namespace('item')
                            ->merge(['class' => "flex items-center gap-2 w-full first-of-type:rounded-t-md last-of-type:rounded-b-md px-4 py-2.5 text-left text-sm focus:bg-primary outline-none focus:text-white hover:bg-primary hover:text-white disabled:text-gray-500"]) 
                     }}>

                </button>
            </template>
            <template x-if="filteredOptions.length === 0">
                <span x-text="statusText()" class="flex items-center gap-2 w-full first-of-type:rounded-t-md last-of-type:rounded-b-md px-4 py-2.5 text-left text-sm outline-none text-gray-500"></span>
            </template>
        </div>
    </div>

</div>