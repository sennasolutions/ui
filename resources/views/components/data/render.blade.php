@props([
    'tag' => 'option',
    'attribute' => 'value',
    'data' => []
])

@php
    $isComponent = (str_contains($tag, "x-senna.input.filter-select-item"));
@endphp

@foreach($data as $key => $value)
    @if($isComponent)
    <x-senna.input.filter-select-item :key="$key">
        {{ $value }}
    </x-senna.input.filter-select-item>
    @else
    <{{ $tag }} {{ $attribute }}="{{ $key }}">{{ $value }}</{{ $tag }}>
    @endif
@endforeach
