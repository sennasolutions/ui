@php
/**
 * @name Textfield
 * @description Icons from heroicons. https://heroicons.com/
 */
@endphp

@props([
    /**
     * @param string name The identifier of the icon. Prefix with an 'h' for heroicons. 'hs-home' is a solid heroicon home icon. 'ho-home' is an outlined heroicon home icon.
     */
    'icon' => ''
])

<div {{ $attributes->namespace('root')->merge(['class' => 'flex space-x-1 items-center ']) }}>
    <x-senna.icon :name="$icon" {{ $attributes }} />
    <span>{{ $slot }}</span>
</div>