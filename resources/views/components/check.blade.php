@props([
    'value' => false
])

@if ($value) 
<x-senna.icon name="hs-check" class="w-5 text-primary"></x-senna.icon>
@else
<x-senna.icon name="hs-x" class="w-5 text-gray-200"></x-senna.icon>
@endif