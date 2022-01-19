@php
/**
 * @name Money
 * @description Money input field. Extends senna.input, so all properties from senna.input can be used on this component.
 */
@endphp

@props([
    /**
     * @param string inputClass Stepsize default: to 0.01
     */
    'step' => '0.01',
    /**
     * @param string inputClass Currency default: &euro;
     */
    'currency' => '&euro;'
])

<x-senna.input {{ $attributes }} data-sn="input.money" type="number" step="{{ $step }}">
    <x-slot name="prefix">
        {!! $currency !!}
    </x-slot>
</x-senna.input>
