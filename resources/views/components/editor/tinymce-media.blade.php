@props([
    'options' => [],
    'value' => null,
    'mediaManagerOptions' => [],
])

@php
    $mediaId = 'tinymce-media-' . str_replace('.', '-', $attributes->wire('model')->value);
@endphp

<div wire:ignore x-data="tinymceMedia(@safe_entangle($attributes->wire('model')), @js($options), '{{ $mediaId }}')">
    <textarea x-ref="textarea"></textarea>
</div>

<div>
    @livewire('senna.media-manager', array_merge([
        'identifier' => $mediaId,
        'callJS' => false,
        'isVisible' => false,
        'displayMode' => Senna\Media\Enums\DisplayMode::Modal,
        'insertMode' => Senna\Media\Enums\InsertMode::Single,
    ], $mediaManagerOptions), key($mediaId))
</div>

@once
    @push('senna-ui-scripts')
        <script src="{{ senna_ui_asset('js/tinymce/tinymce.min.js') }}"></script>

        <script>
            const regexLinkMedia = () => /(?!.*(?:youtube\.com|youtu\.be|vimeo\.com))(?:[A-Za-z][A-Za-z\d.+-]{0,14}:\/\/(?:[-.~*+=!&;:'%@?^${}(),\w]+@)?|www\.|[-;:&=+$,.\w]+@)[A-Za-z\d-]+(?:\.[A-Za-z\d-]+)*(?::\d+)?(?:\/(?:[-.~*+=!;:'%@$(),\/\w]*[-~*+=%@$()\/\w])?)?(?:\?(?:[-.~*+=!&;:'%@?^${}(),\/\w]+))?(?:#(?:[-.~*+=!&;:'%@?^${}(),\/\w]+))?/g;
            document.addEventListener('alpine:init', () => {
                Alpine.data('tinymceMedia', (value, options, mediaId) => ({
                    currentValue: value,
                    editorInstance: null,
                    getYouTubeEmbedUrl(url) {
                        var videoId = '';
                        if (url.indexOf('youtu.be/') !== -1) {
                            videoId = url.split('youtu.be/')[1].split('?')[0].split(' ')[0];
                        } else if (url.indexOf('v=') !== -1) {
                            videoId = url.split('v=')[1].split('&')[0].split(' ')[0];
                        } else if (url.indexOf('/shorts/') !== -1) {
                            videoId = url.split('/shorts/')[1].split('?')[0].split(' ')[0];
                        } else if (url.indexOf('/embed/') !== -1) {
                            videoId = url.split('/embed/')[1].split('?')[0].split(' ')[0];
                        }
                        return videoId ? 'https://www.youtube.com/embed/' + videoId : url;
                    },
                    getVimeoEmbedUrl(url) {
                        var match = url.match(/vimeo\.com\/(\d+)/);
                        return match ? 'https://player.vimeo.com/video/' + match[1] : url;
                    },
                    buildEmbedIframe(src) {
                        return '<iframe src="' + src + '" style="width:100%; aspect-ratio:16/9;" frameborder="0" allowfullscreen></iframe>';
                    },
                    init() {
                        let component = this;

                        tinymce.init({
                            target: this.$refs.textarea,
                            themes: 'modern',
                            height: 300,
                            menubar: false,
                            cleanup: true,
                            plugins: [
                                'lists wordcount paste autolink link code image media',
                            ],
                            media_live_embeds: true,
                            block_formats: 'Paragraaf=p; Kop=h3',
                            toolbar: 'undo redo | formatselect | bold italic | bullist numlist | removeformat link | insertmedia | code',
                            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:15px } img { max-width: 100%; height: auto; } span.mce-preview-object[data-mce-object="iframe"] { display: block; width: 360px; max-width: 100%; aspect-ratio: 16/9; overflow: hidden; } span.mce-preview-object[data-mce-object="iframe"] iframe { display: block; width: 100%; height: 100%; }',
                            object_resizing: 'img',
                            paste_block_drop: true,
                            link_default_target: '_blank',
                            link_assume_external_targets: true,
                            autolink_pattern: new RegExp('^' + regexLinkMedia().source + '$', 'i'),

                            ...options,
                            setup: (editor) => {
                                component.editorInstance = editor;

                                editor.ui.registry.addButton('insertmedia', {
                                    icon: 'image',
                                    tooltip: 'Media invoegen',
                                    onAction: () => {
                                        Livewire.emit(mediaId + ':show');
                                    }
                                });

                                editor.on('init', () => {
                                    if (component.currentValue != null) {
                                        editor.setContent(component.currentValue);
                                    }
                                });

                                component.$watch('currentValue', (newValue) => {
                                    if (newValue && newValue != editor.getContent()) {
                                        editor.resetContent(newValue || '');
                                    }
                                });

                                editor.on('PastePreProcess', (e) => {
                                    var tmp = document.createElement('div');
                                    tmp.innerHTML = e.content;
                                    var text = (tmp.textContent || tmp.innerText || '').trim();

                                    // YouTube
                                    if (/^(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:watch\?v=|shorts\/|embed\/)|youtu\.be\/)/.test(text)) {
                                        var embedUrl = component.getYouTubeEmbedUrl(text);
                                        if (embedUrl !== text) {
                                            e.content = component.buildEmbedIframe(embedUrl);
                                            return;
                                        }
                                    }

                                    // Vimeo
                                    if (/^(?:https?:\/\/)?(?:www\.)?vimeo\.com\/\d+/.test(text)) {
                                        var embedUrl = component.getVimeoEmbedUrl(text);
                                        if (embedUrl !== text) {
                                            e.content = component.buildEmbedIframe(embedUrl);
                                            return;
                                        }
                                    }
                                });

                                editor.on('blur', () => {
                                    component.currentValue = editor.getContent();
                                });
                            }
                        });

                        Livewire.on(mediaId + ':editor:insert', (id, url, hash, type) => {
                            if (component.editorInstance) {
                                var html = '';
                                if (type === 'video-link') {
                                    var embedUrl = component.getYouTubeEmbedUrl(url);
                                    html = component.buildEmbedIframe(embedUrl);
                                } else {
                                    html = '<img src="' + url + '" alt="" style="max-width:100%; height:auto;" />';
                                }
                                component.editorInstance.insertContent(html);
                                component.currentValue = component.editorInstance.getContent();
                            }
                        });
                    }
                }));
            });
        </script>
    @endpush
@endonce
