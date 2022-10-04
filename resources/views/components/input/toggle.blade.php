@props([
    'label' => 'Some label',
    'name' => 'name',
    'value' => false,
    'colors' => []
])

@autowire('label', 'value')

@php
    $colors = array_merge([
        'bg-primary' => 'bg-primary',
        'border-primary' => 'border-primary',
    ], $colors)
@endphp

<div
    wire:ignore
    x-data="{ 
        value: @safeEntangle($attributes->wire('value'))
    }"
    {{ $attributes->merge(['class' => "flex gap-3 items-center"]) }}
    x-id="['toggle']"
>
    <input name="{{ $name }}" :value="value" type="hidden">

    <button
        role="switch"
        type="button"

        class="relative inline-flex w-14 rounded-full py-1 px-0"
        x-bind:class="value ? '{{ $colors['bg-primary'] }} border-2 border-white' : 'bg-white border-2 {{ $colors['border-primary'] }}'"
        
        x-ref="toggle"
        x-on:click="value = ! value"
        x-bind:aria-checked="value"
        x-bind:aria-labelledby="$id('toggle')"
    >
        <span
            aria-hidden="true"
            class="h-5 w-5 rounded-full transition"

            x-bind:class="value ? 'bg-white translate-x-7' : '{{ $colors['bg-primary'] }} translate-x-1'"
        ></span>
    </button>

    <label
        class="cursor-pointer"
        x-bind:id="$id('toggle')"
        @click="$refs.toggle.click(); $refs.toggle.focus()"
    >
        {{ $label }}
    </label>
</div>