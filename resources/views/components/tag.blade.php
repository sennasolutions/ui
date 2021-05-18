@php
/**
 * @name Tag
 * @description A tag element
 */
@endphp

@props([
    /**
     * @param string colorClass String of color classes applied to the button. default: text-white bg-gray-800 ring-gray-800 ring-opacity-30
     */
    'colorClass' => 'bg-gray-600 text-gray-50',
])
<span {{ $attributes->merge(['class' => class_concat('rounded py-1.5 px-2 text-sm', $colorClass)]) }}>
    {{ $slot }}
</span>
