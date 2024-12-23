@php
/**
 * @name Datepicker
 * @description Datepicker component
 */
@endphp

@props([
    /**
     * @param array value The initial value, if wire:model is not used. 
     */
    'value' => null,
    /**
     * @param array mapsConfig The flatpickr config object
     */
    'config' => [
        'allowInput' => true,
    ],
    /**
    * @param array dayClasses An array of classes to apply to specific days, indexed by the day
    */
    'dayClasses' => [],
    /**
     * @param string dayTooltips An array of tooltips to apply to specific days, indexed by the day
     */
    'dayTooltips' => [],
    /**
    * @param boolean assetsOnly Only include the assets, not the component itself
    */
    'assetsOnly' => false
])

@if(!$assetsOnly)
    {{-- Checks for a wire:multiple etc --}}
    @wireProps

    @php
        // The textbox is not shown when inline
        $isInline = $config['inline'] ?? false;
        $textboxClass = $isInline ? 'hidden' : '';
    @endphp

    <div
        {{ $attributes->root()->merge([ 'class' => 'sui-date' ]) }}
        x-data="sennaDatepicker({ value: @entangleProp('value'), config: @entangleProp('config'), dayClasses: @entangleProp('dayClasses'), dayTooltips: @entangleProp('dayTooltips'), })"
        wire:ignore>

        <x-sui.form.textbox x-ref="datepicker" {{ $attributes->namespace('input')->merge([ 'input::class' => $textboxClass ]) }}>
            <x-slot name="prefix">
                @if(isset($prefix))
                    {{ $prefix }}
                @else
                    <x-senna.icon name="ho-calendar" class="textbox__icon text-black" />
                @endif
            </x-slot>
            <x-slot name="suffix">
                @if(isset($suffix))
                    {{ $suffix }}
                @endif
            </x-slot>
        </x-sui.form.textbox>
    </div>
@endif

@once
    @push('senna-ui-scripts')
        <script src="{{ senna_ui_asset('js/flatpicker.min.js') }}"></script>
        <script src="{{ senna_ui_asset('js/flatpicker.nl.min.js') }}"></script>

        <script>
            document.addEventListener('alpine:init', () => {
                flatpickr.localize(flatpickr.l10ns.nl);

                Alpine.data('sennaDatepicker', ({ value, config, dayClasses, dayTooltips }) => ({
                    value,
                    config,
                    dayClasses,
                    dayTooltips,
                    instance: null,
                    init() {
                        this.initDatepicker();

                        this.$watch('value', (value) => {
                            // When the value is changed by the outside, set it on the instance
                            setTimeout(() => {
                                this.instance.setDate(value)
                                this.instance.jumpToDate(value)
                            }, 100)
                        });

                        this.$watch('config', (config) => {
                            // Refresh the datepicker
                            this.initDatepicker();
                        });

                        this.$watch('dayClasses', (config) => {
                            // Refresh the datepicker
                            this.initDatepicker();
                        });

                        this.$watch('dayTooltips', (config) => {
                            // Refresh the datepicker
                            this.initDatepicker();
                        });
                    },
                    /**
                    * The datepicker needs to be initialized
                    */
                    initDatepicker() {
                        if(!this.$refs.datepicker) return;

                        // https://flatpickr.js.org/options/
                        let config = {
                            dateFormat: "d-m-Y",
                            allowInput: true,
                            defaultDate: this.value,
                            onValueUpdate: (selectedDates, dateStr, instance) => {
                                this.value = dateStr
                            },
                            /**
                            * The structure needs to be changed a bit on the calendar to allow
                            * for tooltips and classes, and a more responsive grid
                            */
                            onDayCreate: (dObj, dStr, fp, dayElem) => {
                                let dateString = this.getDateString(dayElem.dateObj);
                                let allClasses = [];
                                let tooltip = '';

                                if (this.dayClasses && typeof this.dayClasses[dateString] !== 'undefined') {
                                    let classes = this.dayClasses[dateString].filter(x => x)

                                    for(let i = 0; i < classes.length; i++) {
                                        allClasses.push(classes[i])
                                    }
                                }

                                if (this.dayTooltips && typeof this.dayTooltips[dateString] !== 'undefined') {
                                    tooltip = this.dayTooltips[dateString]
                                }

                                tooltip = tooltip ? `aria-label="${tooltip}" data-balloon-pos="down"` : ''

                                dayElem.style = 'display: flex; flex: 1 1 auto; line-height: normal; justify-items: center; align-items: center;'
                                dayElem.innerHTML = `<span style="display: flex; align-items: center; justify-content: center;" class="${allClasses.join(" ")}">
                                    <span ${tooltip}>${dayElem.innerHTML}</span>
                                </span>`
                            },
                            /**
                            * Let the user override the config
                            */
                            ...this.config
                        };

                        if (this.instance) {
                            this.instance.destroy()
                        }

                        this.instance = flatpickr(this.$refs.datepicker.querySelector('input'), config);

                        if (config.inline) {
                            this.instance.calendarContainer.classList.add('is-inline')
                        } else {
                            this.instance.calendarContainer.classList.add('is-not-inline')
                        }
                    },
                    /**
                    * Convert a date to a string in the format YYYY-MM-DD
                    * @param Date date
                    * @returns string
                    */
                    getDateString(date) {
                        // https://stackoverflow.com/a/50130338
                        return new Date(date.getTime() - (date.getTimezoneOffset() * 60000 ))
                                .toISOString()
                                .split("T")[0];
                    }
                }))
            })
        </script>
    @endpush
    @push('senna-ui-styles')
        <link rel="stylesheet" href="{{ senna_ui_asset('css/flatpicker.min.css') }}">
        <link rel="stylesheet" href="{{ senna_ui_asset('css/flatpicker.custom.css') }}">
    @endpush
@endonce