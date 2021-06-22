@php
/**
 * @name Date
 * @description Flatpicker datepicker, does work in conjuction with livewire via wire:model
 */
@endphp

@props([
    'type' => 'text',
    /**
     * @param array config The flatpicker config. See https://flatpickr.js.org/options/
     */
    'config' => [
        // maxDate
        // minDate
    ],
    /**
     * @param array value The initial value, if wire:model is not used
     */
    'value' => null,
    /**
     * @param string size 'xl', 'lg' or 'sm'
     */
    'size' => 'lg',
    /**
     * @param string prefixClass The classes for the prefix icon
     */
    'prefixClass' => 'text-black',
    /**
     * @param string error Whether to show an error border on the input
     */
     'error' => false
])

@php
    $inputClass = "" . default_input_chrome($size, $error) . " pl-9";
    $isInline = ($config['inline'] ?? false);
@endphp

<div
    x-data="initDatepicker(@safe_entangle($attributes->wire('model')))"
    x-init='start(@json($config))'
    wire:ignore
    {{-- {{ ($isInline) ? 'wire:ignore' : 'wire:ignore' }} --}}
    {{ $attributes->merge(['class' => 'sn-input-date relative block'])->only('class') }}
    >
    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none {{ $prefixClass }}">
        <span class="opacity-60 sm:text-sm">
            <x-heroicon-s-calendar class="w-4" />
        </span>
    </div>

    <input style="{{ $isInline ? 'display: none !important;' : '' }}" x-ref="flatpicker" class="{{ $inputClass }}" {{ $attributes->merge([
        // 'value' => $value,
        'type' => $type
    ])->except('class') }}>
</div>

@once
    @push('senna-ui-styles')
    <link rel="stylesheet" href="{{ senna_ui_asset('css/flatpicker.min.css') }}">
    <link rel="stylesheet" href="{{ senna_ui_asset('css/flatpicker.theme.css') }}">
    @endpush

    @push('senna-ui-scripts')
    <script src="{{ senna_ui_asset('js/flatpicker.min.js') }}"></script>
    <script src="{{ senna_ui_asset('js/flatpicker.nl.min.js') }}"></script>
    <script>
        flatpickr.localize(flatpickr.l10ns.nl);
        
        function initDatepicker(currentValue) {
            return {
                currentValue: currentValue,
                start(config) {
                    this.config = config
                    this.initDatepicker(this.currentValue);

                    // console.log(JSON.stringify(this.config.enable))
                    // console.log(this.currentValue)

                    // Listen to changes from livewire and set it on Choices
                    // this.$watch('currentValue', (value) => this.setValue(value))

                    // if (window.is_lwd) {
                    //     Livewire.hook('message.processed', (msg, component) => {
                    //         if (component.id === @this.__instance.id) {
                    //             // On update reinitialize
                    //             this.initDatepicker();
                    //         }
                    //     })
                    // }

                    this.$watch('currentValue', value => {
                        // On update reinitialize
                        this.initDatepicker(value)
                    })
                },
                initDatepicker(value) {
                    if(!this.$refs.flatpicker) return;

                    let localConfig = @json(config('senna.ui.datepicker'))

                    this.instance = flatpickr(this.$refs.flatpicker, {
                        ...localConfig,
                        ...this.config,
                        defaultDate: value
                    });
                },
            }
        }
    </script>
    @endpush
@endonce
