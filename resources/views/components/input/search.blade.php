@props([
    'type' => 'text',
    'inputClass' => '',
    'prefixClass' => '',
    'suffixClass' => '',
    'iconClass' => '',
    'transparent' => true,
    'shortcut' => ''
])

@php
    $prefix =  true;
    $suffix = $suffix ?? false;
    $_inputClass = "
        block w-full px-4 py-2.5 text-gray-700 border-gray-300 rounded-md
        dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:focus:border-sui-first
        ocus:ring-sui-first-20 focus:border-sui-first-50
        placeholder-gray-400 focus:placeholder-gray-300
    ";

    $_inputClass .= $transparent ? "pl-12 focus:ring-0 !border-none !bg-transparent" : "pl-12 focus:ring border bg-white";

    $shortcutAttriubtes = $shortcut ? 'x-on:keydown.window.' . $shortcut . '.prevent="$refs.input.focus(); $refs.input.select()"" x-on:keydown.escape="$refs.input.blur()"' : '';

@endphp

<div {!! $shortcutAttriubtes !!} {{ $attributes->merge(['class' => 'sn-input-text relative block', 'x-data' => '{}'])->only('class') }} >
    @if($prefix)
        <div class="{{ class_merge('absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-black opacity-70 sm:text-sm', $prefixClass) }}">
            <x-senna.icon name="hs-search" class="w-5 cursor-pointer {{ $iconClass }}"></x-senna.icon>
        </div>
    @endif
    <input x-ref="input" class="{{ class_concat($_inputClass, $inputClass, ($prefix ? "pl-8" : ""), ($suffix ? "pr-8" : "")) }}"
        {{ $attributes->merge(['type' => $type])->except('class') }}
    />
    @if($suffix)
        <div class="{{ class_merge('absolute inset-y-0 right-0 flex items-center pr-3 text-black opacity-70 sm:text-sm', $suffixClass) }}">
            {{ $suffix }}
        </div>
    @endif
</div>
