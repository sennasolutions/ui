@php
/**
 * @name Tag
 * @description A tag element
 */
@endphp

@props([
    /**
     * @param string colorClass String of color classes applied to the tag. default: text-white bg-gray-800 ring-gray-800 ring-opacity-30
     */
    'colorClass' => 'bg-gray-600 text-gray-50',
    /**
     * @param string size The size of the tag. 'sm' or 'lg' or 'xl'. default: 'lg'
     */
     'size' => 'lg',
    /**
     * @param string sizeClass The classes applied to override the size.
     */
    'sizeClass' => null,
    /**
     * @param string tag The tag to use (default: span)
     */
    'tag' => 'span'
])

@php
    if (!$sizeClass) {
        $sizeClass = "py-1.5 px-2";
        switch($size) {
            case "sm":
                $sizeClass = "py-1 px-1.5 text-xs";
                break;
            case "lg":
                $sizeClass = "py-1.5 px-2 text-sm";
                    break;
            case "xl":
                $sizeClass = "py-2 px-2.5 text-lg";
                break;
        }
    }
@endphp


<{{ $tag }} data-sn='tag' {{ $attributes->merge(['class' => class_concat('rounded', $colorClass, $sizeClass)]) }}>
    {{ $slot }}
</{{ $tag }}>
