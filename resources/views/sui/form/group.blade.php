@php
/**
 * @name Form group
 * @description Group your input with a label element. Also allows for display of error en helpText.
 */
@endphp

@props([
    'label' => '',
    /**
     * @param string for The for attribute which links label to an input id
     */
    'for',
    /**
     * @param boolean isHorizontal Stack the label/input horizontal or vertical
     */
    'isHorizontal' => true,
    /**
     * @param string error Error text
     */
    'error' => false,
    /**
     * @param string helpText Help text
     */
    'helpText' => false,
])

<div {{ $attributes->root() }}>
    <div {{ $attributes->namespace('stack')->merge([
        'class' => "flex space-y-2 flex-col " . (!$isHorizontal ? "sm:flex-row sm:space-x-4 sm:items-center" : "")
    ]) }}>
        @if($label)
            <label {{ $attributes->namespace('label')->merge([
                'class' => 'cursor-pointer shrink-0 ' . (!$isHorizontal ? "w-32" : ""),
                'for' => $for ?? '',
            ]) }}>{!! $label !!}</label>
        @endif
        @if($isHorizontal && ($helpText))
        <div {{ $attributes->namespace('suffix')->merge(['class' => "!mt-0"]) }}>
            <p {{ $attributes->namespace('help')->merge(['class' => "text-sm text-gray-500"]) }}>{!! $helpText !!}</p>
        </div>
        @endif
        <div {{ !($slot instanceof Illuminate\Support\HtmlString) ? $slot?->attributes?->merge(['class' => "flex-grow"]) : '' }}>
            {{ $slot }}
        </div>
        @if($isHorizontal && ($error))
        <x-senna.notice.validation type="error" {{ $attributes->namespace('error') }}>
            {{ $error }}
        </x-senna.notice.validation>
        @endif
    </div>
    @if(!$isHorizontal && ($error || $helpText))
    <div {{ $attributes->namespace('suffix')->merge(['class' => "sm:pl-36"]) }}>
        @if ($error)
        <x-senna.notice.validation type="error" {{ $attributes->namespace('error')->merge(['class' => '!mt-2']) }}>
            {{ $error }}
        </x-senna.notice.validation>
        @endif

        @if ($helpText)
            <p {{ $attributes->namespace('help')->merge(['class' => "mt-2 text-sm text-gray-500"]) }}>{{ $helpText }}</p>
        @endif
    </div>
    @endif
</div>
