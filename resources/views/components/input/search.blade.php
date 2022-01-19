@php
/**
 * @name Search
 * @description Search field. Extends senna.input, so all properties from senna.input can be used on this component.
 */
@endphp

@props([
    /**
     * @param string transparent Whether to show a borderless search field.
     */
    'transparent' => true,
])

@php
    $attributes = $attributes->merge(['inputClass' => $transparent ? '--transparent' : '']);
@endphp

<x-senna.input {{ $attributes }} data-sn="input.search">
    <x-slot name="prefix">
        <x-senna.icon name="hs-search" class="w-5 cursor-pointer"></x-senna.icon>
    </x-slot>
</x-senna.input>
