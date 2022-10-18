@props([
    'search' => '',
    'value' => null,
    'options' => [
        [ 'value' => 1, 'label' => 'Example' ],
        [ 'value' => 2, 'label' => 'Example 2' ],
    ],
    'multiple' => false,
    'placeholder' =>null,
    'config' => [
        'removeItemButton' => true,
        'resetScrollPosition' => false,
        'shouldSort' => false
    ],
])

{{-- Checks for a wire:multiple etc --}}
@wireProps

<div
    x-ref="container"
    
    {{ $attributes->merge(['class' => "w-full"])->whereDoesntStartWith("wire") }}
    wire:ignore
    x-data="{
        value: @entangleProp('value'),
        multiple: @entangleProp('multiple'),
        options: @js($options),
        hasOptionsMethod: @js($attributes->has('wire.method:options')),
        search: @entangleProp('search'),
        config: {
            ...@entangleProp('config'),
        },
        instance: null,
        searchTimeout: null,
        placeholder: null,
        init() {
            this.$nextTick(() => {
                this.initChoices()

                // Find and keep the placeholder for later reference
                this.placeholder = this.instance.config.choices.find(x => x.placeholder)

                this.$refs.select.addEventListener('search', this.onSearch.bind(this) )
                this.$refs.select.addEventListener('change', this.onChange.bind(this) ) 
       

                if (this.value && !this.search) {
                    this.search = this.value

                    this.refreshOptions()
                }
                
                this.refreshOptions()

                {{-- this.$watch('value', () => this.refreshOptions()) --}}
                {{-- this.$watch('options', () => this.refreshOptions()) --}}

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

            let parseOptions = ({ value, label }) => ({
                value: value === null ? '' : value,
                label,
                selected: selection === value || (selection && selection.includes(value)),
            })

            this.instance.clearChoices()  
            this.focusSearch();
            
            if(this.hasOptionsMethod) {
                return this.instance.setChoices(async() => {
                    return (await @wireMethod('options')(this.search)).map(parseOptions)
                })
            } else {
                return this.instance.setChoices(this.options.map(parseOptions))
            }
        },
        onChange() {
            let value = this.instance.getValue(true)
            this.value = value === '' || value === [''] ? null : value
        
            if (!value) {
                this.initChoices()
                this.refreshOptions()
                {{-- this.replacePlaceholder() --}}
            }
        },
        focusSearch() {
            let input = this.$refs.container.querySelector('input[type=search]');
            if (input) {
                input.focus()
            }
        },
        onSearch(event) {
            // debounce
            clearTimeout(this.searchTimeout)

            this.searchTimeout = setTimeout(() => {
                this.search = event.detail.value
                this.refreshOptions().then(x => {
                    this.focusSearch();
                })
            }, 500)
        }
    }"
    
>  

        <select
            x-ref="select" 
            :multiple="multiple"
            />
            @if($placeholder)
                <option value="" disabled selected>{{ $placeholder }}</option>
            @endif
        </select>
  
</div>

@once
    @push('senna-ui-styles')
        {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" /> --}}
        <link rel="stylesheet" href="{{ senna_ui_asset('css/choices.css') }}">

        {{-- <style>
        .choices__input {
           width: auto !important;
        }
        </style> --}}

    @endpush
    @push('senna-ui-scripts')
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    @endpush
@endonce
