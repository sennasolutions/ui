@props([
    'component' => null,
    'data' => []
])

@php
    if (!str_contains($component, "::")) {
        $component = "components." . $component;
    }
@endphp

@component($component, array_merge($data, [
    'attributes' => new Illuminate\View\ComponentAttributeBag($data)
]))
{{ $slot }}
@endcomponent
