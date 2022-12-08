@props([
    'tag' => 'button'
])

<{{ $tag }} {{ $attributes->merge(['class' => 'p-2 w-10 h-10 rounded-lg hover:bg-gray-100 flex items-center justify-center']) }}>
{{ $slot }}
</{{ $tag }}>