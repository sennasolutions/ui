@php
/**
 * @name Lister
 * @description Lets the user create a list of items
 */
@endphp

@props([
    /**
     * @param array value The current list
     */
    'value' => [

    ],
    /**
     * @param string placeholder The placeholder for the input
     */
    'placeholder' => __('Value here'),
    /**
     * @param string placeholder The placeholder for the input
     */
    'labelPlaceholder' => __('Label here'),
    /**
     * @param string placeholder The placeholder for the input
     */
    'valuePlaceholder' => __('Value here'),
    /**
     * @param bool useLabelValueFormat If true, the value will be an array of objects with a value and label property. If false, the value will be an array of strings
     */
    'useLabelValueFormat' => true,
])

{{-- Checks for a wire:placeholder etc --}}
@wireProps

<div {{ $attributes->merge(['class' => "senna-lister border p-4 rounded"]) }}
    :id="id"
    x-data="{
        id: $id('lister'),
        items: @entangleProp('value'),
        labelPlaceholder: @entangleProp('labelPlaceholder'),
        valuePlaceholder: @entangleProp('valuePlaceholder'),
        placeholder: @entangleProp('placeholder'),
        @if($useLabelValueFormat)
        newItem: {},
        @else
        newItem: '',
        @endif
        init() {
           
        },
        remove(item) {
            this.items = this.items.filter(i => i !== item)
        },
        addNewItem() {
            if (this.newItem) {
                this.items = [...this.items, this.newItem]
                @if($useLabelValueFormat)
                this.newItem = {}
                @else
                this.newItem = ''
                @endif
            }
        },
        dragStart(item, ev) {
            let index = this.items.indexOf(item)
            ev.dataTransfer.setData('text/plain', index);
            ev.dataTransfer.effectAllowed = 'move';

            console.log(item, ev.target)
            // Start the dragging
        },
        dragOver(item, ev) {
            ev.dataTransfer.dropEffect = 'move';
        },
        dragEnd(item, ev) {
            let index = ev.dataTransfer.getData('text/plain');
        },
        dropBefore(item, ev) {
            let index = ev.dataTransfer.getData('text/plain');
            let itemToMove = this.items[index]
            let itemDroppedOn = item
            let itemDroppedOnIndex = this.items.indexOf(itemDroppedOn)

            this.items = this.items.filter(i => i !== itemToMove)
            this.items.splice(itemDroppedOnIndex, 0, itemToMove)
        },
        dropAfter(item, ev) {
            let index = ev.dataTransfer.getData('text/plain');
            let itemToMove = this.items[index]
            let itemDroppedOn = item
            let itemDroppedOnIndex = this.items.indexOf(itemDroppedOn)

            this.items = this.items.filter(i => i !== itemToMove)
            this.items.splice(itemDroppedOnIndex + 1, 0, itemToMove)
        }
    }">

    <form class="senna-lister__entry" x-on:submit.prevent="addNewItem">
        <div class="flex gap-2">
            @if($useLabelValueFormat)
            <x-senna.input name="label" x-model="newItem.label" {{ $attributes->namespace('input') }} x-bind:placeholder="labelPlaceholder" />
            <x-senna.input name="value" x-model="newItem.value" {{ $attributes->namespace('input') }} x-bind:placeholder="valuePlaceholder" />
            @else
            <x-senna.input x-model="newItem" {{ $attributes->namespace('input') }} x-bind:placeholder="placeholder" />
            @endif
            <x-senna.button.primary x-on:click="addNewItem" size="sm" {{ $attributes->namespace('submit')->merge(['class' => 'w-12']) }}>
                <x-senna.icon name="hs-plus" class="w-5 h-5" />
            </x-senna.button>
        </div>
    </form>

    <ul class="senna-lister__items mt-4 flex flex-col">
        <template x-for="item in items">
            <li 
                draggable="true" 
                x-on:dragstart="dragStart(item, $event)"
                x-on:dragover.prevent="dragOver(item, $event)"
                x-on:dragend.prevent="dragEnd(item, $event)"
                x-on:drop="dropBefore(item, $event)"
                class="flex flex-col items-center">
                <div {{ $attributes->namespace('item')->merge(['class' => 'senna-lister__item flex w-full gap-2 items-center py-1']) }}>
                    <x-senna.icon name="hs-menu-alt-4" class="w-5 h-5 cursor-move opacity-20" />
                    @if($useLabelValueFormat)
                    <div x-text="item.label"></div>
                    <div x-text="item.value" class="opacity-50"></div>
                    @else
                    <div x-text="item"></div>
                    @endif
                    <x-senna.button.text x-on:click="remove(item)" size="sm" class=" ml-auto shrink-0">
                        <x-senna.icon name="hs-x" class=" text-black w-5 h-5" />
                    </x-senna.button>
                </div>
            </li>
        </template>
        <li x-show="items.length === 0" class="flex flex-col items-center">
            <div {{ $attributes->namespace('item')->merge(['class' => 'senna-lister__item flex w-full gap-2 items-center py-1']) }}>
                {{ __("No items") }}
            </div>
        </li>
    </ul>
</div>