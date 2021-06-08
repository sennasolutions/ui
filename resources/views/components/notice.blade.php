
@php
/**
 * @name Notice
 * @description Give a notice about something to the user
 */
@endphp

@props([
    /**
     * @param string type One of: success, error, info. Default: success
     */
    'type' => 'success'
])

@php
    $bg = "bg-success-color";

    if ($type === "error") {
        $bg = "bg-error-color";
    }

    if ($type === "info") {
        $bg = "bg-info-color";
    }
@endphp

<div {{$attributes->merge(['class' => $bg . ' text-white p-3 flex rounded shadow-lg']) }}>
    @if ($type === "success")
        <x-senna.icon class="w-6 mr-3" name="ho-check"></x-senna.icon>
    @endif
    @if ($type === "error")
        <x-senna.icon class="w-6 mr-3" name="ho-exclamation"></x-senna.icon>
    @endif
    @if ($type === "info")
        <x-senna.icon class="w-6 mr-3" name="ho-information-circle"></x-senna.icon>
    @endif
    <div>
        {{ $slot }}
    </div>
</div>
