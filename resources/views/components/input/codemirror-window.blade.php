@props([
    'config' => [],
    'title' => '',
    'copyClass' => "-top-9 right-2",
    'showCopyButton' => true,
    'value' => null
])

@php
 $value = $value ?? ($slot ? $slot->toHtml() : "");
@endphp

<x-senna.panel.window-dark :title="$title" {{ $attributes->only('class')  }}>
    <x-senna.input.codemirror  :value="$value" :copyClass="$copyClass" :config="$config" :showCopyButton="$showCopyButton" {{ $attributes->except('class')  }} />
</x-senna.panel.window-dark>
