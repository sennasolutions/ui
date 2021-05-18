@php
/**
 * @name Textfield
 * @description A basic textfield, attributes are passed to the input element. Allows for a prefix and a suffix slot.
 */
@endphp

@props([
    'type' => 'text',
    /**
     * @param string inputClass String of classes applied to the input element
     */
    'inputClass' => '',
    /**
     * @param string prefixClass String of classes applied to the prefix element
     */
    'prefixClass' => '',
    /**
     * @param string suffixClass String of classes applied to the suffix element
     */
    'suffixClass' => '',
    /**
     * @param string shortcut Define a keyboard shortcut for focus. 'cmd.a' etc see alpine.
     */
    'shortcut' => ''
])

@php
    $prefix = $prefix ?? false;
    $suffix = $suffix ?? false;
    $inputChrome = default_input_chrome();
    $shortcutAttriubtes = $shortcut ? 'x-on:keydown.window.' . $shortcut . '.prevent="$refs.input.focus(); $refs.input.select()" x-on:keydown.escape="$refs.input.blur()"' : '';
@endphp

<div {!! $shortcutAttriubtes !!} {{ $attributes->merge(['class' => 'sn-input-text relative block', 'x-data' => '{}'])->only('class') }}>
    @if($prefix)
        <div class="{{ class_merge('absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-black opacity-70 sm:text-sm', $prefixClass) }}">
            {{ $prefix }}
        </div>
    @endif
    <input x-ref="input" class="{{ class_concat($inputChrome, $inputClass, ($prefix ? "pl-8" : ""), ($suffix ? "pr-8" : "")) }}"
        {{ $attributes->merge(['type' => $type])->except('class') }}
    />
    @if($suffix)
        <div class="{{ class_merge('absolute inset-y-0 right-0 flex items-center pr-3 text-black opacity-70 sm:text-sm', $suffixClass) }}">
            {{ $suffix }}
        </div>
    @endif
</div>
