{{-- @php
/**
 * @name Text Button
 * @description A text button
 */
@endphp

@props([
    /**
     * @param string tag Tag that is used for this button. For example 'a' or 'button'. default: 'button'
     */
    'tag' => 'button',
    /**
     * @param string colorClass String of color classes applied to the button. default: text-primary-color
     */
    'colorClass' => 'text-primary-color',
    /**
     * @param string type Type that is used for this button. For example 'submit' or 'button'. default: 'button'
     */
    'type' => 'button'
])

<{{ $tag }} type="{{ $type }}" {{ $attributes->merge(['class' => "sn-button-text font-semibold $colorClass hover:underline" ]) }}>
    {{ $slot }}
</{{ $tag }}> --}}
<x-senna.button {{ $attributes }} :textButton="true" colorClass="">{{ $slot }}</x-button.base>
