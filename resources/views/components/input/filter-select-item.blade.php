@props([
    'key' => '',
    'label' => '',
    'name' => '',
    'showDeleteButton' => false
])

@php

if (!$label && isset($slot)) {
    $label = $slot->toHtml();
}
if (!$key) {
    $key = $label;
}

$id = $name . str_replace(" ", "", $label) . $key;

@endphp

<div class="flex items-center content-center" x-show="search.length === 0 || visible.indexOf(`{{ ($key) }}`) >= 0">
    <template x-if="Array.isArray(selected)">
        <x-senna.input.checkbox  id="{{ $id }}" name="{{ $name }}" class="mr-1" value="{{ $key }}" x-model="selected" />
    </template>
    <template x-if="!Array.isArray(selected)">
        <x-senna.input.radio {{ $attributes }} name="{{ $name }}" class="mr-3" id="{{ $id }}" value="{{ $key }}" x-model="selected" />
    </template>
    <label class="cursor-pointer" for="{{ $id }}">{{ $label }}</label>
    @if($showDeleteButton)
    <svg x-on:click="deleteValue('{{ $key }}')" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 ml-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    @endif
</div>
