@php
/**
 * @name Tab
 * @description Tab to be used in x-sui.tabs
 */
@endphp

@props([
    /**
     * @param string name The name of the tab
     */
    'name'
])

<div 
    class="sui-tab"
    x-data="{
        id: '',
        name: '{{ $name }}',
        show: false,
        showIfActive(active) {
            this.show = (this.name == active);
        }
    }"
    wire:ignore.self wire:key="{{ $name }}"
    x-show="show"
    role="tabpanel"
    :aria-labelledby="`tab-${id}`"
    :id="`tab-panel-${id}`"
    {{ $attributes }}
>
    {{ $slot }}
</div>
