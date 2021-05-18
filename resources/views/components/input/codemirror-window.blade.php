@props([
    'config' => [],
    'title' => '',
    'copyClass' => "-top-9 right-2",
    'showCopyButton' => true,
    'val' => null
])

@php
 $val = $val ?? ($slot ? $slot->toHtml() : "");
@endphp

<x-senna.panel.window-dark :title="$title" {{ $attributes->only('class')  }}>
    <x-senna.input.codemirror  :val="$val" :copyClass="$copyClass" :config="$config" :showCopyButton="$showCopyButton" {{ $attributes->except('class')  }} />
</x-senna.panel.window-dark>
