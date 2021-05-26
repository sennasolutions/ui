@props([
    'key' => '',
    'label' => '',
    'name' => ''
])

@php
$id = $name . str_replace(" ", "", $label) . $key;
if (!$label && isset($slot)) {
    $label = $slot->toHtml();
}

@endphp

<div class="flex items-center content-center" x-show="search.length === 0 || visible.indexOf(`{{ ($key) }}`) >= 0">
    <template x-if="Array.isArray(selected)">
        <x-senna.input.checkbox  id="{{ $id }}" name="{{ $name }}" class="mr-1" value="{{ $key }}" x-model="selected" />
    </template>
    <template x-if="!Array.isArray(selected)">
        <x-senna.input.radio {{ $attributes }} name="{{ $name }}" class="mr-3" id="{{ $id }}" value="{{ $key }}" x-model="selected" />
    </template>
    <label class="cursor-pointer" for="{{ $id }}">{{ $label }}</label>
</div>
