@props([
    /**
     * @param string maxWidth The maximum width of the modal. One of: 'sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', '6xl'
     */
     'maxWidth' => '3xl',
    /**
     * @param bool backdrop Whether to show a backdrop
     */
    'backdrop' => true,
    /**
     * @param string value The value given via this attribute or via the slot if not supplied by wire:model
     */
     'value' => null,

    /**
     * @param bool showClose Whether to show a close button
     */
    'showClose' => true,
    /**
     * @param bool showClose Whether to show the header
     */
    'showHeader' => true,
    /**
     * @param bool showClose Whether to show the footer
     */
    'showFooter' => true,
    /**
     * @param string headerClass String of classes applied to header slot. default: p-6
     */
     'headerClass' => 'px-6 py-4 border-b',
    /**
     * @param string headerText The header text
     */
     'headerText' => __('Header'),
    /**
     * @param string mainClass String of classes applied to main slot. default: p-6
     */
    'mainClass' => 'p-6',
    /**
     * @param string footerClass String of classes applied to footer slot. default: px-6 mt-4 py-4 bg-gray-50 flex justify-end items-center space-x-4 rounded-md
     */
    'footerClass' => 'px-6 mt-4 py-4 bg-gray-50 flex justify-end items-center space-x-4 rounded-md',
])

<x-senna.modal {{ $attributes }} :maxWidth="$maxWidth">
    <x-senna.panel :headerClass="$headerClass" :mainClass="$mainClass" :footerClass="$footerClass">
        @if($showHeader)
        <x-slot name="header">
            @if(isset($header))
            {{ $header }}
            @else
            <h3>{{ $headerText }}</h3>
            @endif
        </x-slot>
        @endif

        @if($showClose)
            @if(isset($close))
            {{ $close}}
            @else
            <button type="button" x-on:click="$dispatch('close')" class="absolute right-0 top-0 p-5">
                <x-senna.icon name="hs-x" class="w-6"></x-senna.icon>
            </button>
            @endif
        @endif

        {{ $slot }}

        @if($showFooter)
        <x-slot name="footer">
            @if(isset($footer))
                {{ $footer}}
            @else
                <x-senna.button.text type="button" colorClass="text-gray-700" x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-senna.button.text>

                <x-senna.button.first type="submit">
                    {{ __('Save' )}}
                </x-senna.button.first>
            @endif
        </x-slot>
        @endif
    </x-senna.panel>
</x-senna.modal>
