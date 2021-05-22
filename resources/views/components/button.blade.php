@php
/**
 * @name Button
 * @description Buttons with different colors and sizes.
 */
@endphp

@props([
    'circle' => false,
    /**
     * @param string colorClass String of color classes applied to the button. default: text-white bg-gray-800 ring-gray-800 ring-opacity-30
     */
    'colorClass' => 'text-white bg-gray-800 ring-gray-800 ring-opacity-30',
    /**
     * @param string tag Tag that is used for this button. For example 'a' or 'button'. default: 'button'
     */
    'tag' => 'button',
    /**
     * @param string type Type that is used for this button. For example 'submit' or 'button'. default: 'button'
     */
    'type' => 'button',
    /**
     * @param string size The size of the button. 'sm' or 'lg' or 'xl'. default: 'lg'
     */
    'size' => 'lg',
    /**
     * @param string sizeClass The classes applied to override the size.
     */
    'sizeClass' => null,
])

@php
    $buttonClass = "
        transition duration-100 ease-in-out
        leading-5 transform
        focus:outline-none focus:ring
        font-semibold
        inline-flex space-x-2 items-center
        focus:transform active:scale-95 hover:opacity-90
        shadow-lg
    ";

    $sizeClass = "px-6 py-3";
    switch($size) {
        case "sm":
            $sizeClass = "px-3 py-2 text-sm";
            break;
        case "lg":
            $sizeClass = "px-6 py-3";
                break;
        case "xl":
            $sizeClass = "px-7 py-4 text-lg";
            break;
    }

    $circleClass = $circle ? "p-2 rounded-full justify-center" : $sizeClass . " rounded-md";
@endphp

<{{ $tag }} type="{{ $type }}" {{ $attributes->merge(['class' => class_concat($buttonClass, $circleClass, $colorClass) ]) }}>
    {{ $slot }}
</{{ $tag }}>
