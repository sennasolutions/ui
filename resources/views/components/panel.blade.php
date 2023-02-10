@php
/**
 * @name Panel
 * @description Show a panel with optional header and footer
 */
@endphp

@props([
    /**
     * @param string headerClass String of classes applied to header slot. default: p-6
     */
    'headerClass' => 'px-6 py-4 border-b',
    /**
     * @param string mainClass String of classes applied to main slot. default: p-6
     */
    'mainClass' => 'p-6 h-full',
    /**
     * @param string footerClass String of classes applied to footer slot. default: px-6 mt-4 py-4 bg-gray-50 flex justify-end items-center space-x-4 rounded-md
     */
    'footerClass' => 'px-6 mt-4 py-4 flex justify-end items-center space-x-4 rounded-md',
])

@php
    if(isset($main)) {
        $slot = $main;
    }
@endphp

<section data-sn="panel" {{ $attributes->merge([ 'class' => 'mx-auto border bg-white rounded-md border-gray-300' ]) }}>
    @if(isset($header))
    <div {{ $header->attributes->merge(['class' => $headerClass]) }}>
        {{ $header }}
    </div>
    @endif

    <div class="{{ $mainClass }}">
        {{ $slot}}
    </div>

    @if(isset($footer))
    <div class="{{ $footerClass }}">
        {{ $footer }}
    </div>
    @endif
</section>
