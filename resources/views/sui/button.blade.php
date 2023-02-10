@php
/**
 * @name Button
 * @description Buttons with different colors and sizes. Checkout the theme.blade.php for the coloring scheme.
 */
@endphp

@props([
    /**
     * @param string tag Tag that is used for this button. For example 'a' or 'button'. default: 'button'
     */
    'tag' => 'button',
    /**
     * @param string type Type that is used for this button. For example 'submit' or 'button'. default: 'button'
     */
    'type' => 'button',
    /**
     * @param string theme The theme of the button
     */
    'theme' => null,
    /**
     * @param string variant The variant of the button
     */
    'color' => null,
    /**
     * @param string variant The variant of the button
     */
    'size' => null,
    /**
     * @param string mode Mode 'default' or 'inline'
     */
    'mode' => 'default',
    /**
    * @param string|null ico Icon to prepend
    */
    'icon' => null,
])

@wireProps

@php
$theme = sui_theme($theme);
sui_tooltip($attributes);

switch ($color) {
    case 'primary':
        $colorClass = "sui-button--primary";
        break;
    case 'secondary':
        $colorClass = "sui-button--secondary";
        break;
    case 'white':
        $colorClass = "sui-button--white";
        break;
    case 'gray':
    default:
        $colorClass = "sui-button--gray";
        break;
}

switch ($size) {
    case 'xs':
        $sizeClass = "sui-button--xs";
        break;
    case 'sm':
        $sizeClass = "sui-button--sm";
        break;
    case 'md':
        $sizeClass = "sui-button--md";
        break;
    case 'lg':
        $sizeClass = "sui-button--lg";
        break;
    case 'xl':
        $sizeClass = "sui-button--xl";
        break;
    default:
        $sizeClass = "sui-button--md";
        break;
}

$modeClass ='';

switch ($mode) {
    case 'inline':
        $modeClass = "sui-button--inline";
        break;
}

$hasContent = $slot->isNotEmpty();
$iconClass = $hasContent ? "mr-2" : "";

@endphp

<{{ $tag }} 
    {{ $attributes->merge(['class' => "sui-button $sizeClass sui-theme-$theme $colorClass $modeClass", 'type' => $type ]) }}>
    @isset($prefix)
        <div {{ $prefix->attributes->merge([ 'class' => 'sui-button__prefix' ])}}>
            {{ $prefix }}
        </div>
    @endisset

    @if($icon)
        <x-senna.icon :name="$icon" {{ $attributes->namespace('icon')->merge(['class' => 'sui-button__icon h-5 w-5 ' . $iconClass]) }} />
    @endif

    {{ $slot }}

    @isset($suffix)
        <div {{ $suffix->attributes->merge([ 'class' => 'sui-button__suffix' ])}}>
            {{ $suffix }}
        </div>
    @endisset
</{{ $tag }}>