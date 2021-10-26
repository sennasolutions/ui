@props([
    'isSelected' => false
])

<tr {{ $attributes->merge(['class' => class_concat('text-sm', $isSelected ? 'bg-selected ' : '') ]) }}>
    {{ $slot }}
</tr>
