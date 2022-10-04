@props([
    'label' => 'Some label',
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
    {{ $attributes->merge(['class' => "flex items-center gap-3"]) }}
    x-id="['toggle-label']"
>
    <input type="hidden" name="sendNotifications" :value="value">

    <button
        x-ref="toggle"
        @click="value = ! value"
        type="button"
        role="switch"
        :aria-checked="value"
        :aria-labelledby="$id('toggle-label')"
        :class="value ? '{{ $colors['bg-primary'] }} border-2 border-white' : 'bg-white border-2 {{ $colors['border-primary'] }}'"
        class="relative inline-flex w-14 rounded-full py-1 px-0"
    >
        <span
            :class="value ? 'bg-white translate-x-7' : '{{ $colors['bg-primary'] }} translate-x-1'"
            class="h-5 w-5 rounded-full transition"
            aria-hidden="true"
        ></span>
    </button>

    <label
        @click="$refs.toggle.click(); $refs.toggle.focus()"
        :id="$id('toggle-label')"
        class="cursor-pointer"
    >
        {{ $label }}
    </label>

</div>