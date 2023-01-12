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
                {{-- console.log('value', contents) --}}
                if (this.editor.getValue() !== contents) {
                    this.editor.setValue(contents)
                }
            })
        },
        initCodemirror() {
            let localConfig = @json(config('senna.ui.codemirror'))


            this.editor = window.CodeMirror(this.$refs.container, {
                lineNumbers: true,
                indentWithTabs: false,
                autoRefresh: true,
                tabSize: 2,
                theme: 'dark',
                value: '' + this.value,
                mode: 'application/xml',
                keyMap: 'sublime',
                readOnly: false,
                extraKeys: {'Ctrl-Space': 'autocomplete'},
                ...localConfig,
                ...this.config,
                hintOptions: {
                    hint: CodeMirror.hint.sql,
                    {{-- tables: {
                        users: ['name', 'score', 'birthDate'],
                        countries: ['name', 'population', 'size']
                    }, --}}
                    completeSingle: false,
                    // extra hint words
                    words: [
                        'extra1',
                    ],  
                    ...(localConfig ?? {}).hintOptions ?? {},
                    ...this.config.hintOptions ?? {},
                },
            });

            this.editor.on('keyup', function (cm, event) {
                // no arrow keys or backspace or tab or comma or space escape
                if (event.keyCode >= 37 && event.keyCode <= 40 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 188 || event.keyCode == 32 || event.keyCode == 27) {
                    return;
                }

                if (!cm.state.completionActive && /*Enables keyboard navigation in autocomplete list*/
                    event.keyCode != 13) {        /*Enter - do not open autocomplete list just after item has been selected in it*/ 
                    if (CodeMirror.commands.autocomplete) {
                        CodeMirror.commands.autocomplete(cm, null, {completeSingle: false});
                    }
                }
            });

            // Value change
            this.editor.on('change', (editor) => {
                setTimeout(() => {
                    this.value = editor.getValue();
                }, 100)
            });
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
