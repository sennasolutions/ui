@props([
    'component' => null,
    'data' => [],
    'slots' => []
])

@php
    if (!str_contains($component, "::")) {
        $component = "components." . $component;
    }
@endphp

@component($component, array_merge($data, [
    'attributes' => new Illuminate\View\ComponentAttributeBag($data),
], $slots))
{{ $slot }}
@endcomponent
