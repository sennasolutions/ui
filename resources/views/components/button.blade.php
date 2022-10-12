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
    /**
     * @param string|null icon Prepend with an icon
     */
    'icon' => null,
    /**
     * @param string iconPosition Position of the icon, 'left' 'right'
     */
    'iconPosition' => 'left',
    /**
     * @param string iconClass Classes applied to the icon
     */
    'iconClass' => 'w-5 h-5',
    /**
     * @param string|bool wire:indicator If true, will show a loading indicator. Provide a string to specify the loading target (wire:click)
     */
    // 'wire:indicator' => false // it's implemented in the code
])

@php
    $buttonClass = join(' ', [
        '-outer',
        // 'loading',
        $textButton ? '--text' : '',
        !$sizeClass ? '--' . $size : $sizeClass,
        $circle ? '--circle' : '',
        $textButton || $colorClass ? $colorClass : '--default',
        'focus:transform active:scale-95 hover:opacity-90', // Didnt work in class,
        $icon ? 'flex items-center gap-1' : '',
    ]);


    // Automatic loading
    $wireIndicator = $attributes->get('wire:indicator', false);

    if ($wireIndicator) {
        $atts = $attributes->getAttributes();

        $loadingTarget = is_string($wireIndicator) ? $wireIndicator : $atts['wire:click'] ?? false;

        $atts['wire:loading.delay.class'] = "loading";
        $atts['x-data'] = "{ loading: false }";

        if ($loadingTarget) {
            $atts['wire:target'] = $loadingTarget;

            if (str($loadingTarget)->startsWith('event:') ) {
                $loadingTarget = (string) str($loadingTarget)->replace('event:', '');

                $atts['x-on:click'] = "loading=true";
                $atts['x-init'] = "Livewire.on('$loadingTarget', () => { loading = false })";
                $atts['x-bind:class'] = "loading ? 'loading' : ''";
            }
        }

        $attributes->setAttributes($atts);
    }
@endphp

<{{ $tag }} 
    {{ $tag == "button" ? 'type=' . $type . '' : '' }} 
    {{ $attributes->merge(['data-sn' => 'button', 'class' => $buttonClass ]) }}>
    @if($icon)
        @if($iconPosition == 'left')
            <x-senna.icon :name="$icon" :class="$iconClass" />
        @endif
        <div>{{ $slot }}</div>
        @if($iconPosition == 'right')
            <x-senna.icon :name="$icon" :class="$iconClass" />
        @endif
    @else
        {{ $slot }}
    @endif
</{{ $tag }}>

@once
@push('senna-styles')
<style>
    [data-sn='button'].loading {
        position: relative;
        text-indent: 200%;
        white-space: nowrap;
        overflow: hidden;
        pointer-events: none;
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
@endpush
@endonce