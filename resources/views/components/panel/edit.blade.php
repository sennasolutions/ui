@props([
    'innerClass' => '',
    'buttonsClass' => '',
    'footerClass' => '',
    'headerClass' => '',
])

<x-senna.panel {{ $attributes->merge(['class' => '!p-0'])->only('class') }}>
    <form {{ $attributes->except('class') }}>
        <div class="p-7 {{ $innerClass }}">
            {{ $slot }}
        </div>

        @if(isset($buttons))
        <div class="px-6 mt-4 py-4 text-black bg-gray-50 flex justify-end items-center space-x-4 rounded-md {{ $buttonsClass }}">
            {{ $buttons }}
        </div>
        @endif
        @if(isset($footer))
        <div class="px-6 mt-4 py-4 text-black bg-gray-50 flex justify-end items-center space-x-4 rounded-md {{ $footerClass }}">
            {{ $footer }}
        </div>
        @endif
    </form>
</x-senna.panel>
