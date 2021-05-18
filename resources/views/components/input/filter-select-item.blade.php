@props([
    'key' => '',
    'label' => ''
])

@php
$id = str_replace(" ", "", $label) . $key;
@endphp

<div class="flex items-center content-center" x-show="search.length === 0 || visible.indexOf('{{ ($key) }}') >= 0">
    <template x-if="Array.isArray(selected)">
        <x-senna.input.checkbox  id="{{ $id }}" class="mr-1" value="{{ $key }}" x-model="selected" />
    </template>
    <template x-if="!Array.isArray(selected)">
        <x-senna.input.radio class="mr-3" id="{{ $id }}" value="{{ $key }}" x-model="selected" />
    </template>
    <label class="cursor-pointer" for="{{ $id }}">{{ __($label) }}</label>
</div>
