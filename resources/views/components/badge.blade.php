@props([
    'colorClass' => 'bg-gray-100 text-gray-700',
])
<span data-sn='badge' {{ $attributes->merge(['class' => class_concat('rounded-full p-0.5 w-5 h-5 text-xs text-center', $colorClass)]) }}>
    {{ $slot }}
</span>
