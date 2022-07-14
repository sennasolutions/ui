@props([
    'options' => [],
    /**
     * @param string value The value given via this attribute or via the slot if not supplied by wire:model
     */
    'value' => null
])

<div>
    <textarea x-ref="textarea" x-data="initTinymce(@safe_entangle($attributes->wire('model')), @js($options))">

    </textarea>
</div>


@once
    @push('senna-ui-styles')
        <style>

        </style>
    @endpush

    @push('senna-ui-scripts')
        <script src="{{ senna_ui_asset('js/tinymce/tinymce.min.js') }}"></script>

        <script>
            function initTinymce(value, options) {
                return {
                    currentValue: value,
                    init() {
                        console.log('INIT', value)

                        let tiny = tinymce.init({
                            target: this.$refs.textarea,
                            themes: 'modern',
                            height: 300,
                            menubar: false,
                            cleanup: true,
                            plugins: [
                                'lists wordcount paste link autolink code',
                            ],
                            block_formats: 'Paragraaf=p; Kop=h3',
                            toolbar: 'undo redo | formatselect | bold italic | bullist numlist | removeformat link | code',
                            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:15px }',
                            paste_block_drop: true,
                            default_link_target: '_blank',
                            link_assume_external_targets: true,

                            ...options,
                            setup: (editor) => {
                                editor.on('init', (ev) => {
                                    console.log('EDITOR INIT')
                                    if (this.currentValue != null) {
                                        editor.setContent(this.currentValue);
                                    }
                                })

                                function putCursorToEnd() {
                                    debugger
                                    editor.selection.select(editor.getBody(), true);
                                    editor.selection.collapse(false);
                                }

                                this.$watch('currentValue', (newValue) => {
                                    if (newValue && newValue != editor.getContent()) {
                                        editor.resetContent(newValue || '');
                                        // putCursorToEnd();
                                        // editor.setContent(newValue);
                                    }
                                });

                                editor.on('blur', (ev) => {
                                    console.log('BLUR')
                                    this.currentValue = editor.getContent();
                                })
                            }
                        });
                    }
                }
            }


      </script>
    @endpush
@endonce
