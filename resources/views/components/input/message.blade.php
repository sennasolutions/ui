@php
/**
 * @name Message
 * @description Message
 */
@endphp

@props([
    /**
     * @param string message The message to display
     */
    'message' => null,
])
<div class="bg-gray-50 rounded-lg p-5">
    {!! $message !!}
</div>
