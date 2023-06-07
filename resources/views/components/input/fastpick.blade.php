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
            <div {{ $attributes->namespace('trigger')->merge(['class' => "text-primary inline-flex gap-1 items-center justify-center"]) }} x-show="options.length > 1" class="">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" {{ $attributes->namespace('icon')->merge(['class' => "w-3 h-3"]) }}>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                </svg>
                <span x-text="valueText"></span>
            </div>
            <div {{ $attributes->namespace('trigger')->merge(['class' => "text-primary inline-flex gap-1 items-center justify-center"]) }} x-show="options.length === 1" class="">
                <span x-text="valueText"></span>
            </div>
        </x-slot>
        <x-slot name="content" class="flex flex-col gap-2">
            <template x-for="option in options" v-bind:key="option.value">
                <x-senna.dropdown.item {{ $attributes->namespace('item') }} x-on:click="value = option.value">
                    <span x-text="option.label"></span>
                </x-senna.dropdown.item>
            </template>
        </x-slot>
    </x-senna.dropdown>
</div>