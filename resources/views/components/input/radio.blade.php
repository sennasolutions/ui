@php
/**
 * @name Radio
 * @description Radio buttons
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
     * @param string label The label text
     */
    'label' => null,
])

<label {{ $attributes->merge(['class' => 'sn-input-check cursor-pointer inline-flex space-x-2 items-center'])->only('class') }}>
    <input type="radio" class="cursor-pointer transition duration-50 ease-in-out  text-primary-color checked:border-none focus:ring-primary-color-30 border-gray-300 rounded-full shadow-sm focus:ring {{ $inputClass }}" {!! $attributes->except('class') !!}>
    <span class="{{ class_merge($labelClass) }}">
        {{ $label ?? $slot }}
    </span>
</label>
