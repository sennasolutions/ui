@php
/**
 * @name Modal
 * @description The modal component that is the foundation for modal.panel
 */
@endphp

@props([
    /**
     * @param string maxWidth The maximum width of the modal. One of: 'sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', '6xl'
     */
    'maxWidth',
    /**
     * @param bool backdrop Whether to show a backdrop
     */
    'backdrop' => true,
    /**
     * @param string value The value given via this attribute or via the slot if not supplied by wire:model
     */
     'value' => null,
])

@php
// $id = $id ?? md5($attributes->wire('model'));

$maxWidth = [
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
    '3xl' => 'sm:max-w-3xl',
    '4xl' => 'sm:max-w-4xl',
    '5xl' => 'sm:max-w-5xl',
    '6xl' => 'sm:max-w-6xl',
][$maxWidth ?? '2xl'];

@endphp

<div
    data-sn="modal"
    x-data="initModal(@safe_entangle($attributes->wire('model')))"
    x-on:close.stop="showMe = false"
    x-on:keydown.escape.window="showMe = false"
    {{-- x-on:keydown.tab.prevent="$event.shiftKey || nextFocusable().focus()" --}}
    {{-- x-on:keydown.shift.tab.prevent="prevFocusable().focus()" --}}
    x-show="showMe"
    class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50"
    style="display: none;"
>
    @if($backdrop)
    <div data-sn="modal-backdrop" x-show="showMe" class="fixed inset-0 transform transition-all" x-on:click="showMe = false" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transitioutn:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0">
        <div class="absolute inset-0 bg-gray-900 opacity-60"></div>
    </div>
    @endif

    <div data-sn="modal-contents" x-show="showMe" {{ $attributes->merge(['class' => "relative transition-all sm:w-full sm:mx-auto " . $maxWidth ])->except('wire:model') }}
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
        {{ $slot }}
    </div>
</div>

@once
    @push('senna-ui-scripts')
        <script>
            function initModal(value) {
                return {
                    showMe: value,
                    focusables() {
                        debugger
                        // All focusable element types...
                        let selector = 'a, button, input:not([type=\'hidden\'], textarea, select, details, [tabindex]:not([tabindex=\'-1\'])'

                        return [...this.$el.querySelectorAll(selector)]
                            // All non-disabled elements...
                            .filter(el => ! el.hasAttribute('disabled'))
                    },
                    firstFocusable() { return this.focusables()[0] },
                    lastFocusable() { return this.focusables().slice(-1)[0] },
                    nextFocusable() { return this.focusables()[this.nextFocusableIndex()] || this.firstFocusable() },
                    prevFocusable() { return this.focusables()[this.prevFocusableIndex()] || this.lastFocusable() },
                    nextFocusableIndex() { return (this.focusables().indexOf(document.activeElement) + 1) % (this.focusables().length + 1) },
                    prevFocusableIndex() { return Math.max(0, this.focusables().indexOf(document.activeElement)) -1 },
                    init() {
                        this.$watch('showMe', value => {
                            if (value) {
                                document.body.classList.add('overflow-y-hidden');
                            } else {
                                document.body.classList.remove('overflow-y-hidden');
                            }
                        })
                    }
                }
            }
        </script>
    @endpush
@endonce
