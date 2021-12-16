@props([
    'api_key' => config('senna.ui.tinymce.apiKey', 'no-api-key'),
    /**
     * @param string value The value given via this attribute or via the slot if not supplied by wire:model
     */
    'value' => null
])

<div>
    <textarea x-ref="textarea" x-data="initTinymce(@safe_entangle($attributes->wire('model')))">
    <p>Dit is eentest </p>
    </textarea>
</div>


@once
    @push('senna-ui-styles')
        <style>
           
        </style>
    @endpush

    @push('senna-ui-scripts')
        <script src="https://cdn.tiny.cloud/1/{{ $api_key }}/tinymce/5/tinymce.min.js" referrerpolicy="origin" ></script>
        

        <script>
            function initTinymce(value) {
                return {
                    currentValue: value,
                    init() {
                        console.log('INIT', value)

                        let tiny = tinymce.init({
                            target: this.$refs.textarea,
                            themes: 'modern',
                            height: 300,
                            menubar: false,
                            plugins: [
                                'lists wordcount',
                            ],
                            toolbar: 'bold italic | bullist numlist | removeformat',
                            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:15px }',
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