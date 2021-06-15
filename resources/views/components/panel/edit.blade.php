@props([
    'paddingClass' => '',
    'buttonsClass' => '',
    'footerClass' => '',
    'headerClass' => '',
    'formTag' => 'form'
])

<x-senna.panel {{ $attributes->merge(['class' => '!p-0'])->only('class') }}>
    <{{ $formTag }} {{ $attributes->except('class') }}>
        <div class="p-7 {{ $paddingClass }}">
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
    </{{ $formTag }}>
</x-senna.panel>
