@props([
    'key' => null,
    'label' => '',
    'name' => '',
    'showDeleteButton' => false,
    'showCheckRadio' => true,
    /**
     * @param bool allowHtml Do not escape the value and show html
     */
     'allowHtml' => false
])

@php

if (!$label && isset($slot)) {
    $label = $slot->toHtml();
}
if ($key === null) {
    $key = $label;
}

$id = str($name)->slug() . str($label)->slug() . $key;

@endphp

<div data-sn="input.filter-select-item" {{ $attributes->merge(['class' => "flex items-center content-center"]) }} x-show="search.length === 0 || visible.indexOf(`{{ ($key) }}`) >= 0">
    @if($showCheckRadio)
        <template wire:key="tplc-{{ $id }}" x-if="Array.isArray(selected)">
            <input wire:key="check-{{ $id }}" id="{{ $id }}" name="{{ $name }}" value="{{ $key }}" x-model="selected" type="checkbox" class="-checkbox-inner mr-3">
        </template>
        <template wire:key="tplr-{{ $id }}" x-if="!Array.isArray(selected)">
            <input wire:key="radio-{{ $id }}" name="{{ $name }}" id="{{ $id }}" value="{{ $key }}" x-model="selected" type="radio" class="mr-3 cursor-pointer transition duration-50 ease-in-out  text-primary checked:border-none focus:ring-primary-300 border-gray-300 rounded-full shadow-sm focus:ring">
        </template>
    @else
        <label wire:key="label1-{{ $id }}" class="w-full">
            @if($allowHtml){!! $name !!}@else{{ $name }}@endif
        </label>
    @endif
    <label wire:key="label2-{{ $id }}" class="cursor-pointer w-full" for="{{ $id }}">
        @if($allowHtml){!! $label !!}@else{{ $label }}@endif
    </label>
    @if($showDeleteButton)
    <svg x-on:click="deleteValue('{{ $key }}')" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 ml-auto cursor-pointer flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    @endif
</div>
