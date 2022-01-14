@php
/**
 * @name Textfield
 * @description This avatar component displays an image and falls back to an SVG with initials.
 */
@endphp

@props([
    /**
     * @param string img The image url
     */
    'img' => '',
    /**
     * @param string textColor Textcolor of the fallback SVG. default: var(--primary-900)
     */
    'textColor' => 'var(--primary-900)',
    /**
     * @param string bgColor background of the fallback SVG. default: var(--primary-200)
     */
    'bgColor' => 'var(--primary-200)',
    /**
     * @param string sizeClass Specify the classes for the size
     */
    'sizeClass' => 'w-10 h-10'
])

@php
    /**
     * @param slot slot The name of the character
     */
    $name = trim($slot->toHtml());
@endphp

@if($img && !str_contains($img, "ui-avatars.com"))
<img {{ $attributes->merge([
    'src' => $img,
    'class' => 'grow-0 shrink-0  rounded-full object-cover ' . $sizeClass,
    'alt' => $name
    ]) }} />
@else

@php
    $parts = explode(" ", $name);

    if (count($parts) == 1) {
        $parts = str_split($name, 1);
        $text = strtoupper(($parts[0][0] ?? '') . ($parts[1][0] ?? ''));
    } else {
        $text = strtoupper(($parts[0][0] ?? '') . ($parts[count($parts) - 1][0] ?? ''));
    }
@endphp



<svg {{ $attributes->merge(['class' => $sizeClass . ' grow-0 shrink-0  rounded-full bg-white']) }} xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 64 64" version="1.1">
    <rect fill="{{ $bgColor }}" cx="32" width="64" height="64" cy="32" r="32"/>
    <text x="50%" y="50%"
    style="color: {{ $textColor }}; line-height: 1;font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Fira Sans', 'Droid Sans', 'Helvetica Neue', sans-serif;"
    alignment-baseline="middle"
    text-anchor="middle"
    font-size="32"
    font-weight="400"
    dy=".1em"
    dominant-baseline="middle"
    fill="{{ $textColor }}">
        {{ $text }}
    </text>
</svg>
@endif
