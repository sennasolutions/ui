@php
/**
 * @name Checkbox
 * @description A basic checkbox, attributes are passed to the input element.
 */
@endphp

@props([
    /**
     * @param string inputClass String of classes applied to the input element
     */
    'inputClass' => '',
    /**
     * @param string labelClass String of classes applied to the label element
     */
    'labelClass' => '',
    /**
     * @param string label Contents of the label
     */
    'label' => null
])

<label data-sn="input.checkbox" {{ $attributes->merge(['class' => '-checkbox-outer'])->only(['class', 'wire:key']) }}>
    <input type="checkbox" class="-checkbox-inner  {{ $inputClass }}" {!! $attributes->except(['class', 'wire:key']) !!}>
    <span class="{{ class_merge("-checkbox-label", $labelClass) }}">
        {{ $label ?? $slot }}
    </span>
</label>
