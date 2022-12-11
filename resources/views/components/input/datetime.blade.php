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
        'enableTime' => true,
        'allowInput' => true,
        'dateFormat' => 'd-m-Y H:i',
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
     'error' => false,
     /**
     * @param string inputClass String of classes applied to the input element
     */
    'inputClass' => '',
])

<x-senna.input.date {{ $attributes }} :type="$type" :config="$config" :value="$value" :size="$size" :prefixClass="$prefixClass" :error="$error" :inputClass="$inputClass" />