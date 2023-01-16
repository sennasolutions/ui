@php
/**
 * @name Url
 * @description Url input field. 
 */
@endphp

<x-senna.input 
    {{ $attributes->merge([
        'inputClass' => '!pl-9',
        'x-data' => 'initUrl(\'' . $attributes->wire('model')?->value() . '\')',
        'x-on:change' => 'inputChange()',
        'x-on:focus' => 'inputChange()',
        'x-on:blur' => 'inputChange()',
    ]) }} 
    data-sn="input.url">
    <x-slot name="prefix">
        <x-senna.icon name="hs-globe-alt" class="w-4 h-4" />
    </x-slot>
</x-senna.input>

@once
@push('scripts')
    <script>
        window.initUrl = function(field_name) {
            return {
                init() {
                    this.$nextTick(() => this.$refs.input.attributes.autofocus && this.$refs.input.focus() )
                },
                inputChange() {
                    let value = this.$event.target.value;
                    let newValue = value

                    if (value.indexOf("http") !== 0) {
                        newValue = "https://" + value;
                    }

                    if (this.$wire && value !== newValue) {
                        this.$wire.set(field_name, newValue);
                    }
                    
                    this.$refs.input.value = newValue;
                }
            }
        }
    </script>
@endpush
@endonce