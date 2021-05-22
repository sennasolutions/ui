@php
/**
 * @name Codemirror
 * @description Init a codemirror instance on the page.
 */
@endphp

@props([
    /**
     * @param array config Config object to pass onto codemirror
     */
    'config' => [],
    /**
     * @param bool showCopyButton Whether to show a copy button
     */
    'showCopyButton' => false,
    /**
     * @param string copyClass String of class applied to the copy-button
     */
    'copyClass' => 'top-1 right-2',
    /**
     * @param string val The value given via this attribute or via the slot if not supplied by wire:model
     */
    'val' => null
])

@php
 $val = $val ?? ($slot ? $slot->toHtml() : "");
@endphp

<div
    x-data="initCodemirror(@safe_entangle($attributes->wire('model')))"
    x-init='init(@json($config))'
    x-on:cm-refresh.window="refresh"
    {{ $attributes->merge(['class' => 'bg-gray-800 cm-wrapper relative']) }}
    wire:ignore>
    <div class="cm-container" x-ref="container"></div>
    @if($showCopyButton)
    <div class="absolute z-10 {{ $copyClass }}" x-on:click="copy">
        <x-senna.button.text x-show="!copied" colorClass="text-white">
            <x-senna.icon name="ho-clipboard" class="w-5"></x-senna.icon>
        </x-senna.button.text>
        <x-senna.button.text x-show="copied" colorClass="text-white">
            <x-senna.icon name="ho-clipboard-check" class="w-5"></x-senna.icon>
        </x-senna.button.text>
    </div>
    @endif
</div>

@once
    @push('senna-ui-styles')
    <link rel="stylesheet" href="{{ senna_ui_asset('css/codemirror.css') }}">
    <style>
        .cm-wrapper, .cm-container, .CodeMirror {
            height: 100%;
            with: 100%;
        }
    </style>
    @endpush

    @push('senna-ui-scripts')
    <script src="{{ senna_ui_asset('js/codemirror.js') }}"></script>
    <script>
        function initCodemirror(currentValue) {
            return {
                copied: false,
                currentValue: currentValue,
                copy() {
                    console.log(this.$refs.container)
                    let textarea = this.$refs.container.querySelector('textarea')
                    // textarea.style.display = 'block'
                    textarea.select();
                    document.execCommand('copy');
                    this.editor.execCommand('selectAll');
                    document.execCommand('copy');
                    this.copied = true
                },
                refresh() {
                    console.log('refresh')
                    this.editor.refresh();
                },
                init(config) {
                    this.config = config
                    this.initCodemirror();
                    // if (window.is_lwd) {
                    //     Livewire.hook('message.processed', (msg, component) => {
                    //         if (component.id === @this.__instance.id) {
                    //             // On update reinitialize
                    //             this.initCodemirror();
                    //         }
                    //     })
                    // }
                },
                initCodemirror() {
                    if(!this.$refs.container) return;

                    let localConfig = @json(config('senna.ui.codemirror'))

                    this.editor = window.CodeMirror(this.$refs.container, {
                        lineNumbers: true,
                        indentWithTabs: false,
                        tabSize: 2,
                        theme: 'dark',
                        value: this.currentValue,
                        mode: 'xml',
                        theme: 'generator',
                        keyMap: 'sublime',
                        readOnly: true,
                        ...localConfig,
                        ...this.config,
                    });

                    this.editor.setOption("theme", 'dark');
                    // this.editor.setOption("readOnly", true)
                },
                setValue(value) {
                    this.editor.setValue(value)
                }
            }
        }
    </script>
    @endpush
@endonce
