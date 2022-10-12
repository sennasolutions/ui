@props([
    'tag' => 'a'
])
<{{ $tag }} {{ $attributes->merge(['class' => 'text-left cursor-pointer w-full block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition']) }}>{{ $slot }}</{{ $tag }}>
