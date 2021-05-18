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
    'items' => []
])

@php
    $defaultChrome = default_input_chrome();
@endphp

<div {{ $attributes->merge(['class' => 'sn-input-select-native'])->only('class') }}>
  <select class="form-select {{ $defaultChrome }} {{ $inputClass }}" {{ $attributes->except('class') }}>
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
