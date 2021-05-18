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
    'label' => null,
])

<label {{ $attributes->merge(['class' => 'sn-input-check cursor-pointer inline-flex space-x-2 items-center'])->only('class') }}>
    <input type="checkbox" class="cursor-pointer transition duration-50 ease-in-out rounded checked:border-none border-gray-300 w-[1.15rem] h-[1.15rem] text-primary-color shadow-sm focus:border-primary-color-50 focus:ring focus:ring-primary-color-30 {{ $inputClass }}" {!! $attributes->except('class') !!}>
    <span class="{{ class_merge($labelClass) }}">
        {{ $label ?? $slot }}
    </span>
</label>
