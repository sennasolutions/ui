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
    'val' => ''
])

@php
    $inputClass = "" . default_input_chrome() . " pl-9";
@endphp

<div
    x-data='initDatepicker(@entangle($attributes->wire('model')))'
    x-init='init(@json($config))'
    wire:ignore.self
    {{ $attributes->merge(['class' => 'sn-input-date relative block'])->only('class') }}
    >
    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
        <span class="opacity-60 sm:text-sm">
            <x-heroicon-s-calendar class="w-4" />
        </span>
    </div>

    <input x-ref="flatpicker" class="{{ $inputClass }}" {{ $attributes->merge([
        'value' => $val,
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
