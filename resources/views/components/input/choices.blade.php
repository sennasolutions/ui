@props([
    'search' => '',
    'value' => 1,
    'options' => [
        [ 'value' => 1, 'label' => 'Example' ],
        [ 'value' => 2, 'label' => 'Example 2' ],
    ],
    'multiple' => false,
    'placeholder' => '-- Selecteer --',
    'config' => [
        'removeItemButton' => false,
        'resetScrollPosition' => false,
    ],
])

{{-- Checks for a wire:multiple etc --}}
@autowire()

<div
    {{ $attributes->merge(['class' => "w-full"])->whereDoesntStartWith("wire") }}
    wire:ignore
    x-data="{
        value: @safeEntangle('wire:value'),
        multiple: @safeEntangle('wire:multiple'),
        options: @safeEntangle('wire:options'),
        search: @safeEntangle('wire:search'),
        config: @safeEntangle('wire:config'),
        instance: null,
        init() {
            this.$nextTick(() => {
                {{-- console.log(this.options) --}}
                this.initChoices()
                this.refreshOptions()

                this.$refs.select.addEventListener('change', this.onChange.bind(this) )
                this.$refs.select.addEventListener('search', this.onSearch.bind(this) )
                
                this.$watch('value', () => this.refreshOptions())
                this.$watch('options', () => this.refreshOptions())
                this.$watch('config', () => {
                    this.initChoices()
                    this.refreshOptions()
                })
            })
        },
        initChoices() {
            if (this.instance) this.instance.destroy()

            this.instance = new Choices(this.$refs.select, this.config)
        },
        refreshOptions() {
            let selection = this.multiple ? this.value : [this.value]

            this.instance.clearChoices()

            this.instance.setChoices(this.options.map(({ value, label }) => ({
                value,
                label,
                selected: selection.includes(value),
            })))

            console.log(this.instance)
        },
        onChange() {
            this.value = this.instance.getValue(true)
        },
        onSearch(event) {
            // debounce
            clearTimeout(this.searchTimeout)

            this.searchTimeout = setTimeout(() => {
                this.search = event.detail.value
            }, 150)
        }
    }"
    
>
    <select
          x-ref="select" 
          :multiple="multiple"
        />
        @if($placeholder)
        <option value="">{{ $placeholder }}</option>
        @endif
    </select>
</div>

@once
    @push('senna-ui-styles')
        {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" /> --}}
        <link rel="stylesheet" href="{{ senna_ui_asset('css/choices.css') }}">

        {{-- <style>
            .choices {
                color: #000;
                width: 100%;
            }
            .choices__list--dropdown {
                z-index: 100;
            }
        </style> --}}

    @endpush
    @push('senna-ui-scripts')
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    @endpush
@endonce
