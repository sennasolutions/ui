@php
/**
 * @name Datetime
 * @description Datepicker component with time
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
        'enableTime' => true,
        'allowInput' => true,
        'dateFormat' => 'd-m-Y H:i',
    ],
    /**
    * @param array dayClasses An array of classes to apply to specific days, indexed by the day
    */
    'dayClasses' => [],
    /**
     * @param string dayTooltips An array of tooltips to apply to specific days, indexed by the day
     */
    'dayTooltips' => [],
])

<x-senna.form.date {{ $attributes->merge([
    'value' => $value,
    'config' => $config,
    'dayClasses' => $dayClasses,
    'dayTooltips' => $dayTooltips,
], escape: false) }} />