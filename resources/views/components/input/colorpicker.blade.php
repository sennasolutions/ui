@php
/**
 * @name Colorpicker
 * @description Init a colorpicker instance on the page.
 */
@endphp

@props([
 
])

{{-- Checks for a wire:placeholder etc --}}
@wireProps

<x-senna.input {{ $attributes }} :attributes="$attributes" type="text" data-coloris />

@once
    @push('senna-ui-styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/mdbassit/Coloris@latest/dist/coloris.min.css"/>
    @endpush

    @push('senna-ui-scripts')
        <script src="https://cdn.jsdelivr.net/gh/mdbassit/Coloris@latest/dist/coloris.min.js"></script>
    @endpush
@endonce
