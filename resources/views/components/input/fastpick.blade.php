@props([
    'options' => [
        [ 'value' => 0, 'label' => 'Option' ],
        [ 'value' => 1, 'label' => 'Another option' ],
    ],
    'value' => null,
    'placeholder' => 'Make a choice'
])

@wireProps()

<div {{ $attributes->root() }} x-data="{
    value: @entangleProp('value'),
    options: @entangleProp('options'),
    placeholder: @entangleProp('placeholder'),
    valueText: '',
    init() {
        this.refreshValueText()
        this.$watch('value', () => this.refreshValueText())
    },
    refreshValueText() {
        this.valueText = this.options.find(option => option.value === this.value)?.label

        if (!this.valueText) {
            this.valueText = this.placeholder
        }
    }
}">
    <x-senna.dropdown {{ $attributes->namespace('dropdown')->merge(['align' => 'left']) }}>
        <x-slot name="trigger">
            <x-senna.button.text x-show="options.length > 1" iconClass="w-5 h-5 text-primary" class="text-current gap-0" iconPosition="right" icon="hs-chevron-down">
                <span x-text="valueText"></span>
            </x-senna.button.text>
            <x-senna.button.text x-show="options.length === 1" class="text-current !font-normal gap-0">
                <span x-text="valueText"></span>
            </x-senna.button.text>
        </x-slot>
        <x-slot name="content" class="flex flex-col gap-2">
            <template x-for="option in options">
                <x-senna.dropdown.item {{ $attributes->namespace('item') }} v-bind:key="option.value" x-on:click="value = option.value">
                    <span x-text="option.label"></span>
                </x-senna.dropdown.item>
            </template>
        </x-slot>
    </x-senna.dropdown>
</div>