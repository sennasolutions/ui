@php
/**
 * @name Select choices
 * @description A select element using choices.js
 */
@endphp

@props([
    /**
     * @param array items Items can be provided as an array (key/value) via this attribute or just by using the option tags in the slot.
     */
    'items' => null,
    /**
     * @param string placeholder Placeholder value
     */
    'placeholder' => 'Please select an option',
    /**
     * @param array placeholder Config passed to the choices.js instance
     */
    'config' => [],
    /**
     * @param array val The default value if wire:model is not used
     */
    'val' => '',
])

<div
    {{ $attributes->merge(['class' => 'flex-grow'])->only('class') }}
    x-data='initSelect(@safe_entangle($attributes->wire('model')))'
	x-init='init(@json($config))'
    x-ref="wrapper"
	>
    <select {{ $attributes->whereDoesntStartWith('wire:model')->except('class') }} x-ref="selectChoices" class="x-select">
        @if($placeholder)
            @if($config['removeItemButton'] ?? true)
                <option value="">{{ $placeholder }}</option>
            @else
                <option value="" disabled>{{ $placeholder }}</option>
            @endif
        @endif

        @if ($items)
            @foreach($items as $value => $label)
                <option value="{{ $value }}">{{ __($label) }}</option>
            @endforeach
        @else
            {{ $slot }}
        @endif
    </select>
</div>

@once
    @push('senna-ui-styles')
        <link rel="stylesheet" href="{{ senna_ui_asset('css/choices.css') }}">

        <style>
            .choices {
                color: #000;
                width: 100%;
            }
            .choices__list--dropdown {
                z-index: 100;
            }
        </style>
    @endpush

    @push('senna-ui-scripts')
    <script src="{{ senna_ui_asset('js/choices.min.js') }}"></script>

    <script>
        function initSelect(currentValue) {
            return {
                currentValue: currentValue,
                init(config) {
                    this.config = config;
                    this.initChoices();

                    // Listen to changes from livewire and set it on Choices
                    this.$watch('currentValue', (value) => this.setValue(value))

                    // Set the initial value
                    this.setValue(this.currentValue)

                    if (window.is_lwd) {
                        Livewire.hook('message.processed', (msg, component) => {
                            if (component.id === @this.__instance.id) {
                                // On update reinitialise
                                this.initChoices();
                                this.setValue(this.currentValue)
                            }
                        })
                    }
                },
                initChoices() {
                    if (!this.$refs.selectChoices) return;

                    let $el = this.$refs.selectChoices;

                    this.choices = new Choices($el, {
                        itemSelectText: '',
                        removeItemButton: true,
                        ...this.config
                    });

                    // Listen to changes and send it to livewire component model
                    this.$refs.selectChoices.addEventListener('change', (event) => {
                        let value = this.choices.getValue();
                        this.currentValue = Array.isArray(value) ? value.map(x => x.value) : value.value;
                    }, false );
                },
                setValue(value) {
                    // Normalize value, it has to be string or an array of strings
                    if (value)
                        value = Array.isArray(value) ? value.map(x => x + "") : value + "";
                    else {
                        value = ""
                    }
                    this.choices.setChoiceByValue(value);
                },
            }
        }
    </script>
    @endpush
@endonce
