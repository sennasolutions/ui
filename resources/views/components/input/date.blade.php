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
     * @param string Whether to show an error border on the input
     */
     'error' => false
])

@php
    $inputClass = "" . default_input_chrome($size, $error) . " pl-9";
    $isInline = ($config['inline'] ?? false);
@endphp

<div
    x-data='initDatepicker(@safe_entangle($attributes->wire('model')))'
    x-init='init(@json($config))'
    {{ ($isInline) ? 'wire:ignore' : 'wire:ignore.self' }}
    {{ $attributes->merge(['class' => 'sn-input-date relative block'])->only('class') }}
    >
    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none {{ $prefixClass }}">
        <span class="opacity-60 sm:text-sm">
            <x-heroicon-s-calendar class="w-4" />
        </span>
    </div>

    <input style="{{ $isInline ? 'display: none !important;' : '' }}" x-ref="flatpicker" class="{{ $inputClass }}" {{ $attributes->merge([
        'value' => $value,
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
    <script>
        function initDatepicker(currentValue) {
            return {
                currentValue: currentValue,
                init(config) {
                    this.config = config
                    this.initDatepicker();

                    console.log(JSON.stringify(this.config.enable))

                    // Listen to changes from livewire and set it on Choices
                    // this.$watch('currentValue', (value) => this.setValue(value))

                    if (window.is_lwd) {
                        Livewire.hook('message.processed', (msg, component) => {
                            if (component.id === @this.__instance.id) {
                                // On update reinitialize
                                this.initDatepicker();
                            }
                        })
                    }
                },
                initDatepicker() {
                    if(!this.$refs.flatpicker) return;

                    let localConfig = @json(config('senna.ui.datepicker'))

                    this.instance = flatpickr(this.$refs.flatpicker, {
                        ...localConfig,
                        ...this.config,
                    });
                },
                setValue(value) {

                }
            }
        }
    </script>
    @endpush
@endonce
