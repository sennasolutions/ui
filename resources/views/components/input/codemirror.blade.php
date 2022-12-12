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
     * @param string value The value given via this attribute or via the slot if not supplied by wire:model
     */
    'value' => null
])

{{-- Checks for a wire:multiple etc --}}
@wireProps

@php
 $slotContents = $slot->toHtml();
 $value = $value ?? ($slot && $slotContents ? $slotContents : null);
@endphp

<div
    data-sn="input.codemirror"
    x-data="{
        copied: false,
        config: @entangleProp('config'),
        value: @entangleProp('value'),
        editor: null,
        copy() {
            let textarea = this.$refs.container.querySelector('textarea')
            // textarea.style.display = 'block'
            textarea.select();
            document.execCommand('copy');
            this.editor.execCommand('selectAll');
            document.execCommand('copy');
            this.copied = true
        },
        refresh() {
            this.editor.refresh();
        },
        init() {
            this.initCodemirror();

            this.$watch('value', (contents) => {
                {{-- this.refresh(); --}}
                if (this.editor.getValue() !== contents) {
                    this.editor.setValue(contents)
                }
            })
        },
        initCodemirror() {
            {{-- if(!this.$refs.container) return; --}}

            let localConfig = @json(config('senna.ui.codemirror'))

            this.editor = window.CodeMirror(this.$refs.container, {
                lineNumbers: true,
                indentWithTabs: false,
                tabSize: 2,
                theme: 'dark',
                value: '' + this.value,
                mode: 'application/xml',
                theme: 'generator',
                keyMap: 'sublime',
                readOnly: false,
                ...localConfig,
                ...this.config,
            });

            // Value change
            this.editor.on('change', (editor) => {
                this.value = editor.getValue();
                console.log(this.value)
            });

            {{-- this.editor.setOption('mode', 'application/xml'); --}}
        },
        setValue(value) {
            this.editor.setValue(value)
        }
    }"
    x-on:cm-refresh.window="refresh"
    {{ $attributes->merge(['class' => 'bg-gray-800 cm-wrapper relative']) }}
    wire:ignore
    >
    
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
    @endpush
@endonce
