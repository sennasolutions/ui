@php
/**
 * @name Input group
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
     * @param string stack Stack the label/input 'horizontal' or 'vertical'. Default: 'horizontal'
     */
    'stack' => 'horizontal',
    /**
     * @param string error Error text
     */
    'error' => false,
    /**
     * @param string helpText Help text
     */
    'helpText' => false,
    /**
     * @param string labelClass String of classes applied to the label element
     */
     'labelClass' => '',
    /**
     * @param string stackClass String of classes applied to the stack element
     */
    'stackClass' => '',
    /**
     * @param string errorClass String of classes applied to the error element
     */
    'errorClass' => '',
    /**
     * @param string helpClass String of classes applied to the help element
     */
    'helpClass' => '',
    /**
     * @param string slotClass String of classes applied to the slot element
     */
    'slotClass' => '',
    /**
     * @param string suffixClass String of classes applied to the suffix element
     */
    'suffixClass' => '',
    /**
     * @param string label Label text
     */
])

@php
    $isHor = $stack === "horizontal";
@endphp

<div data-sn="input.group" {{ $attributes->merge(['class' => '' ]) }}>
    <div class="{{ class_concat($stackClass, "flex space-y-1 flex-col", (!$isHor ? "sm:flex-row sm:space-x-4 sm:items-center" : "")) }}">
        @if($label)
        <label @if(isset($for))for="{{ $for }}"@endif class="{{ class_concat($labelClass, 'mb-2 dark:text-gray-200 cursor-pointer flex-shrink-0', (!$isHor ? "w-32" : "") ) }}">{{ $label }}</label>
        @endif
        @if($isHor && ($helpText))
        <div class="mb-2 {{ $suffixClass }}">
            <p class="text-sm text-gray-500 {{ $helpClass }}">{{ $helpText }}</p>
        </div>
        @endif
        <div class="flex-grow {{ $slotClass }}">
            {{ $slot }}
        </div>
        @if($isHor && ($error))
        <x-senna.notice.validation wire:key="error" type="error" class="{{ $errorClass }}">
            {{ $error }}
        </x-senna.notice.validation>
        @endif
    </div>
    @if(!$isHor && ($error || $helpText))
    <div class="{{ $suffixClass }} sm:pl-36">
        @if ($error)
        <x-senna.notice.validation  wire:key="error" type="error" class="!mt-2 {{ $errorClass }}">
            {{ $error }}
        </x-senna.notice.validation>
        @endif

        @if ($helpText)
            <p class="mt-2 text-sm text-gray-500 {{ $helpClass }}">{{ $helpText }}</p>
        @endif
    </div>
    @endif
</div>
