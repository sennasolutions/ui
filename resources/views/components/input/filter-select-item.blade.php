@props([
    'key' => null,
    'label' => '',
    'name' => '',
    'showDeleteButton' => false,
    'showCheckRadio' => true
])

@php

if (!$label && isset($slot)) {
    $label = $slot->toHtml();
}
if ($key === null) {
    $key = $label;
}

$id = $name . str_replace(" ", "", $label) . $key;

@endphp

<div data-sn="input.filter-select-item" class="flex items-center content-center" x-show="search.length === 0 || visible.indexOf(`{{ ($key) }}`) >= 0">
    @if($showCheckRadio)
    <template wire:key="tplc-{{ $id }}" x-if="Array.isArray(selected)">
        <x-senna.input.checkbox wire:key="check-{{ $id }}" id="{{ $id }}" name="{{ $name }}" class="mr-1" value="{{ $key }}" x-model="selected" />
    </template>
    <template wire:key="tplr-{{ $id }}" x-if="!Array.isArray(selected)">
        <x-senna.input.radio {{ $attributes }} wire:key="radio-{{ $id }}" name="{{ $name }}" class="mr-3" id="{{ $id }}" value="{{ $key }}" x-model="selected" />
    </template>
    @else
        <label wire:key="label1-{{ $id }}">{{ $name }}</label>
    @endif
    <label wire:key="label2-{{ $id }}" class="cursor-pointer" for="{{ $id }}">{{ $label }}</label>
    @if($showDeleteButton)
    <svg x-on:click="deleteValue('{{ $key }}')" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 ml-auto cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    @endif
</div>
