@php
/**
 * @name Button
 * @description Buttons with different colors and sizes. Checkout the theme.blade.php for the coloring scheme.
 */
@endphp

@props([
    'circle' => false,
    'textButton' => false,
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
     * @param string size The size of the button. 'xs', 'sm' or 'lg' or 'xl'. default: 'lg'
     */
    'size' => 'lg',
    /**
     * @param string sizeClass The classes applied to override the size.
     */
    'sizeClass' => null,
])


@once
<style>
    [data-sn='button'].loading {
        position: relative;
        text-indent: 200%;
        white-space: nowrap;
        overflow: hidden;
    }

    [data-sn='button'].loading::after {
        display: block;
        content: "";
        width: 20px;
        height: 20px;
        background-color: currentColor;
        position: absolute;
        top: 50%;
        left: 50%;
        margin-left: -10px;
        margin-top: -10px;

        border-radius: 100%;
        -webkit-animation: sk-scaleout 1.0s infinite ease-in-out;
        animation: sk-scaleout 1.0s infinite ease-in-out;
    }

    @-webkit-keyframes sk-scaleout {
        0% { -webkit-transform: scale(0) }
        100% {
            -webkit-transform: scale(1.0);
            opacity: 0;
        }
    }

    @keyframes sk-scaleout {
        0% {
            -webkit-transform: scale(0);
            transform: scale(0);
        } 100% {
            -webkit-transform: scale(1.0);
            transform: scale(1.0);
            opacity: 0;
        }
    }
</style>
@endonce


@php
    $buttonClass = "sn-button font-semibold hover:underline text-sui-first";
    $sizeClass = "";

    if(!$textButton) {
        $buttonClass = "
            transition duration-100 ease-in-out
            leading-5 transform
            focus:outline-none focus:ring
            font-semibold
            inline-flex space-x-2 items-center
            focus:transform active:scale-95 hover:opacity-90
            shadow-lg
        ";

        if (!$sizeClass) {
            $sizeClass = "px-6 py-3";
            switch($size) {
                case "xs":
                    $sizeClass = "px-2 py-1 text-xs";
                    break;
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
        }
    }

    $circleClass = $circle ? "p-2 rounded-full justify-center" : $sizeClass . " rounded-md";

    // Automatic loading
    $atts = $attributes->getAttributes();
    $wireClick = $atts['wire:click'] ?? false;

    if ($wireClick) {
        // wire:target="saveLocation" wire:loading.delay.class="loading"
        $atts['wire:loading.delay.class'] = "loading";
        // $atts['wire:target'] = $wireClick;
        $attributes->setAttributes($atts);
    }
@endphp

<{{ $tag }} data-sn="button" {{ $tag == "button" ? 'type="' . $type . '"' : '' }} {{ $attributes->merge(['class' => class_concat($buttonClass, $circleClass, $colorClass) ]) }}>
    {{ $slot }}
</{{ $tag }}>
