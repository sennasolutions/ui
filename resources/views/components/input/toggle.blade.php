@props([
    'label' => 'Some label',
    'name' => 'name',
    'value' => false,
    'colors' => []
])

@wireProps()

@php
    $colors = array_merge([
        'bg-primary' => 'bg-primary',
        'border-primary' => 'border-primary',
    ], $colors);

    $classes = "";
    $buttonClasses = "";

    if($attributes->namespace('button')->get('disabled')) {
        $classes .= " opacity-50";
        $buttonClasses .= " pointer-events-none";
    }
@endphp

<div
    
    x-data="{ 
        value: @entangleProp('value')
    }"
    {{ $attributes->root()->merge(['class' => "flex gap-3 items-center cursor-pointer $classes" ]) }}
    x-id="['toggle']"
>
    <input  name="{{ $name }}" :value="value" type="hidden">

    <button
        
        role="switch"
        type="button"
        x-bind:class="value ? '{{ $colors['bg-primary'] }} border-2 border-white' : 'bg-white border-2 {{ $colors['border-primary'] }}'"
        x-ref="toggle"
        x-on:click="value = ! value"
        x-bind:aria-checked="value"
        x-bind:aria-labelledby="$id('toggle')"
        
        {{ $attributes->namespace('button')->merge(['class' => "relative inline-flex w-14 rounded-full py-1 px-0 $buttonClasses"]) }}
    >
        <span
            aria-hidden="true"
            class="h-5 w-5 rounded-full transition"

            x-bind:class="value ? 'bg-white translate-x-7' : '{{ $colors['bg-primary'] }} translate-x-1'"
        ></span>
    </button>

    <label
        {{ $attributes->namespace('label')->merge(['class' => "cursor-pointer"]) }}
        x-bind:id="$id('toggle')"
        @click="$refs.toggle.click(); $refs.toggle.focus()"
    >
        {{ $label }}
    </label>
</div>