<x-senna.button {{ $attributes->merge(['class' => 'text-primary focus:outline-none focus:ring focus:ring-primary-300 focus:ring-offset-2 ']) }} :textButton="true" colorClass="">
{{ $slot }}
</x-senna.button>
