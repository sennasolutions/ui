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

<div {{ $attributes->merge(['class' => 'sn-input-group' ]) }}>
    <div class="{{ class_concat($stackClass, "flex space-y-2 flex-col", (!$isHor ? "sm:flex-row sm:space-x-4 sm:items-center" : "")) }}">
        <label for="{{ $for }}" class="{{ class_concat($labelClass, 'text-gray-700 dark:text-gray-200 cursor-pointer flex-shrink-0', (!$isHor ? "w-32" : "") ) }}">{{ $label }}</label>
        <div class="flex-grow">
            {{ $slot }}
        </div>
    </div>

    <div class="{{ $suffixClass }} {{ (!$isHor ? "sm:pl-36" : "") }}">
        @if ($error)
        <div class="mt-1 text-red-500 text-sm {{ $errorClass }}">{{ $error }}</div>
        @endif

        @if ($helpText)
            <p class="mt-2 text-sm text-gray-500 {{ $helpClass }}">{{ $helpText }}</p>
        @endif
    </div>
</div>
