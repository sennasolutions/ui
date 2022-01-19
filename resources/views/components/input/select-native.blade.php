@php
/**
 * @name Select native
 * @description A native select dropdown
 */
@endphp

@props([
    /**
     * @param string placeholder Placeholder value
     */
    'placeholder' => null,
    /**
     * @param string inputClass String of classes applied to the input element
     */
    'inputClass' => 'flex',
    /**
     * @param array items Items can be provided as an array (key/value) via this attribute or just by using the option tags in the slot.
     */
    'items' => [],
    /**
     * @param string size 'xl', 'lg' or 'sm'
     */
     'size' => 'lg',
     /**
     * @param string error Whether to show an error border on the input
     */
     'error' => false
])

@php
    $inputClass = "-inner --$size " . ($error ? '--error' : '') . ' ' . $inputClass;
@endphp

<div {{ $attributes->merge(['class' => '-outer', 'data-sn' => 'input.select-native'])->only(['class', 'data-sn']) }}>
  <select x-ref="select" class="form-select {{ $inputClass }}" {{ $attributes->except(['class', 'data-sn']) }}>
    @if ($placeholder)
        <option disabled value="">{{ $placeholder }}</option>
    @endif

    @forelse ($items as $key => $item)
        <option value="{{ $key }}">{{ $item }}</option>
    @empty
        {{ $slot }}
    @endforelse
  </select>
</div>
