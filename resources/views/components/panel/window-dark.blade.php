@php
/**
 * @name Window
 * @description Provide a nice window with fake buttons and an optional title
 */
@endphp

@props([
    /**
     * @param string title Optional title
     */
    'title' => ''
])

<div {{ $attributes->merge([
    'class' => "bg-gray-800 rounded-md pb-3"
]) }}>
    <div class="topbar relative p-3 mb-2 flex space-x-1.5 border-b border-gray-700">
        <div class="bg-red-400 rounded-full w-3 h-3"></div>
        <div class="bg-yellow-400 rounded-full w-3 h-3"></div>
        <div class="bg-green-400 rounded-full w-3 h-3"></div>
        <div class="absolute opacity-70 mt-[1px] left-16 top-2 text-sm text-white">
            {{ $title}}
        </div>
    </div>
{{ $slot }}
</div>
