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
        'noCalendar' => true,
        'enableTime' => true,
        'dateFormat' => 'H:i',
        'inline' => false
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
     'error' => false,
     /**
     * @param string inputClass String of classes applied to the input element
     */
    'inputClass' => '',
])

@php
    $inputClass = "-inner --$size " . ($error ? '--error' : '') . ' ' . $inputClass;

    $isInline = ($config['inline'] ?? false);
@endphp

<div
    data-sn="input.date"
    x-data="initDatepicker(@safe_entangle($attributes->wire('model')))"
    x-json='@json($config)'
    wire:ignore
    {{-- {{ ($isInline) ? 'wire:ignore' : 'wire:ignore' }} --}}
    {{ $attributes->merge(['class' => '-outer'])->only('class') }}
    >
    <div class="-prefix {{ $prefixClass }}">
        <x-heroicon-s-calendar class="w-4" />
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
                init() {
                    this.config = JSON.parse(this.$el.getAttribute('x-json'))
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
