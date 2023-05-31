@php
/**
 * @name Textarea
 * @description A textarea, attributes are passed to the textarea element.
 */
@endphp

@props([
    /**
     * @param string inputClass String of classes applied to the input element
     */
    'inputClass' => '',
    /**
     * @param string size 'xl', 'lg' or 'sm'
     */
     'size' => 'lg',
     /**
     * @param string error Whether to show an error border on the input
     */
     'error' => false,
     /**
      * @param string autogrow Whether to size up the textarea as the user types
      */
     'autogrow' => false
])

@php
    $inputClass = "-inner --$size " . ($error ? '--error' : '') . ' ' . $inputClass;
@endphp

<div data-sn="input.textarea" {{ $attributes->merge(['class' => '-outer' ])->only('class') }}>
    <textarea wire:ignore x-data="{
        init() {
            if ({{ $autogrow ? 'true' : 'false' }}) {
                this.$el.addEventListener('input', () => {
                    this.resize();
                });

                this.resize();
            }
        },
        resize() {
            this.$el.style.height = 'auto';
            this.$el.style.height = this.$el.scrollHeight + 'px';
        }
    }" x-init="init" {{ $attributes->except('class') }} class="{{ $inputClass }}"></textarea>
</div>
