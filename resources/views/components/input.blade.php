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
    'shortcut' => '',
    /**
     * @param string size 'xl', 'lg' or 'sm'
     */
    'size' => 'lg',
    /**
     * @param string error Whether to show an error border on the input
     */
    'error' => false
])

@php
    $prefix = $prefix ?? false;
    $suffix = $suffix ?? false;
    // $isAutofocus = $attributes['autofocus'] ?? false;
    // $inputChrome = default_input_chrome($size, $error);
    $inputClass = "-inner --$size " . ($error ? '--error' : '') . " " . $inputClass;

    $shortcutsAttr = $shortcut ? 'x-on:keydown.window.' . $shortcut . '.prevent="$refs.input.focus(); $refs.input.select()" x-on:keydown.escape="$refs.input.blur()"' : '';
@endphp

<div
    {!! $shortcutsAttr !!} 
    {{ $attributes->merge([
        'x-data' => '{}', 
        'x-init' => '$nextTick(() => $refs.input.attributes.autofocus && $refs.input.focus() )', 
        'class' => '-outer', 
        'data-sn' => 'input.text'
        ])->only(['class', 'data-sn', 'x-data', 'x-init']) }}>

    @if($prefix)
        <div class="{{ class_concat('-prefix', $prefixClass) }}">
            {{ $prefix }}
        </div>
    @endif
    <input x-ref="input" class="{{ class_concat($inputClass, ($prefix ? "--prefixed" : ""), ($suffix ? "--suffixed" : "")) }}"
        {{ $attributes->merge(['type' => $type])->except(['class', 'data-sn', 'x-data', 'x-init']) }}
    />
    @if($suffix)
        <div class="{{ class_concat('-suffix', $suffixClass) }}">
            {{ $suffix }}
        </div>
    @endif
</div>
