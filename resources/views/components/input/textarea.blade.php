@php
/**
 * @name Textarea
 * @description A textarea, attributes are passed to the textarea element.
 */
@endphp

@props([
    /**
     * @param string inputClass String of classes applied to the input element
     */
    'inputClass' => '',
    /**
     * @param string size 'xl', 'lg' or 'sm'
     */
     'size' => 'lg',
     /**
     * @param string error Whether to show an error border on the input
     */
     'error' => false
])

@php
    $inputClass = "-inner --$size " . ($error ? '--error' : '') . ' ' . $inputClass;
@endphp

<div data-sn="input.textarea" class="{{ $attributes->merge(['class' => '-outer' ])->only('class') }}">
    <textarea class="{{ $inputClass }}" {{ $attributes->except('class') }}>{{ $slot }}</textarea>
</div>
