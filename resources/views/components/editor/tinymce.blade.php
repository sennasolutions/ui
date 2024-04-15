@props([
    'options' => [],
    /**
     * @param string value The value given via this attribute or via the slot if not supplied by wire:model
     */
    'value' => null
])

<div wire:ignore x-data="tinymce(@safe_entangle($attributes->wire('model')), @js($options))">
    <textarea x-ref="textarea" >

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
            const regexLink = () => /(?!.*(?:youtube\.com|youtu\.be|vimeo\.com))(?:[A-Za-z][A-Za-z\d.+-]{0,14}:\/\/(?:[-.~*+=!&;:'%@?^${}(),\w]+@)?|www\.|[-;:&=+$,.\w]+@)[A-Za-z\d-]+(?:\.[A-Za-z\d-]+)*(?::\d+)?(?:\/(?:[-.~*+=!;:'%@$(),\/\w]*[-~*+=%@$()\/\w])?)?(?:\?(?:[-.~*+=!&;:'%@?^${}(),\/\w]+))?(?:#(?:[-.~*+=!&;:'%@?^${}(),\/\w]+))?/g;
            document.addEventListener('alpine:init', () => {
                Alpine.data('tinymce', (value, options) => ({
                    currentValue: value,
                    init() {
                        let tiny = tinymce.init({
                            target: this.$refs.textarea,
                            themes: 'modern',
                            height: 300,
                            menubar: false,
                            cleanup: true,
                            // /(?:[A-Za-z][A-Za-z\d.+-]{0,14}:\/\/(?:[-.~*+=!&;:'%@?^${}(),\w]+@)?|www\.|[-;:&=+$,.\w]+@)[A-Za-z\d-]+(?:\.[A-Za-z\d-]+)*(?::\d+)?(?:\/(?:[-.~*+=!;:'%@$(),\/\w]*[-~*+=%@$()\/\w])?)?(?:\?(?:[-.~*+=!&;:'%@?^${}(),\/\w]+))?(?:#(?:[-.~*+=!&;:'%@?^${}(),\/\w]+))?/g;
                            plugins: [
                                'lists wordcount paste autolink link code',
                            ],
                            block_formats: 'Paragraaf=p; Kop=h3',
                            toolbar: 'undo redo | formatselect | bold italic | bullist numlist | removeformat link | code',
                            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:15px }',
                            paste_block_drop: true,
                            link_default_target: '_blank',
                            link_assume_external_targets: true,
                            autolink_pattern: new RegExp('^' + regexLink().source + '$', 'i'),

                            ...options,
                            setup: (editor) => {
                                editor.on('init', (ev) => {
                                    if (this.currentValue != null) {
                                        editor.setContent(this.currentValue);
                                    }
                                })

                                function putCursorToEnd() {
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
                                    this.currentValue = editor.getContent();
                                })
                            }
                        });
                    }
                }))
            })
      </script>
    @endpush
@endonce
