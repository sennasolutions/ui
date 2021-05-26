@props([
    'name' => '',
    'id' => null,
    'tag' => 'h2'
])

@php
    $id = $id ?? Illuminate\Support\Str::slug($name);
@endphp

<div page-search="#{{ $id }}" page-search-name="{{ $name }}" {{ $attributes }}>
    <{{ $tag }} class="!mb-1" id="{{ $id }}" nav-group="{{ $name }}">{{ $name }}</{{ $tag }}>
    <p class="text-lg mt-3 text-gray-400">
        {{ $slot }}
    </p>
</div>
