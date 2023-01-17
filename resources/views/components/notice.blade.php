
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
    $bg = "bg-success";

    if ($type === "error" || $type === "danger") {
        $bg = "bg-danger";
    }

    if ($type === "info") {
        $bg = "bg-info";
    }
@endphp

<div data-sn='notice' {{$attributes->merge(['class' => $bg . ' text-white p-4 pr-6 flex rounded shadow-lg']) }}>
    @if ($type === "success")
        <x-senna.icon class="w-10 mr-6 ml-3 shrink-0" name="ho-check"></x-senna.icon>
    @endif
    @if ($type === "error")
        <x-senna.icon class="w-10 mr-6 ml-3 shrink-0" name="ho-exclamation-triangle"></x-senna.icon>
    @endif
    @if ($type === "info")
        <x-senna.icon class="w-10 mr-6 ml-3 shrink-0" name="ho-information-circle"></x-senna.icon>
    @endif
    <div>
        {{ $slot }}
    </div>
</div>
