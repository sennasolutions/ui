@php
/**
 * @name Notice (for validation)
 * @description Give a notice about something to the user
 */
@endphp

@props([
    /**
     * @param string type One of: success, error, info. Default: success
     */
    'type' => 'success',
])

@php
    $text = "text-success-color";

    if ($type === "error") {
        $text = "text-error-color";
    }

    if ($type === "info") {
        $text = "text-info-color";
    }
@endphp

<div data-sn='notice.validation' {{$attributes->merge(['class' => $text . ' mt-1 items-center flex gap-1 text-sm']) }}>
    @if ($type === "success")
        <x-senna.icon class="w-4" name="hs-check"></x-senna.icon>
    @endif
    @if ($type === "error")
        <x-senna.icon class="w-4" name="hs-exclamation"></x-senna.icon>
    @endif
    @if ($type === "info")
        <x-senna.icon class="w-4" name="hs-information-circle"></x-senna.icon>
    @endif

    <span>{{ $slot }}</span>
</div>
