@props([
    'active',
    'tabClasses' => '',
    'value' => null
])

@php
if (isset($active)) $value = $active;
@endphp

<div
    {{ $attributes->merge(['class' => 'w-full'])->only('class') }}
    x-data="initTabs(@safe_entangle($attributes->wire('model')) )"
    x-init='init'
>
    <div class="mb-3 border-b"
         role="tablist"
    >
        <template x-for="(tab, index) in tabHeadings"
                  :key="index"
        >
            <button x-text="tab"
                    type="button"
                    x-on:click="tabClick(tab, $dispatch, $nextTick)"
                    class='transition duration-50 ease-in-out -mb-px px-4 uppercase text-gray-900 text-xs font-bold rounded-none py-2 focus:outline-none {{ $tabClasses }}'
                    {{ $attributes->except('class') }}
                    :class="tab === activeTab ? 'active border-b-2 border-primary-color' : 'text-gray-400'"
                    :id="`tab-${index + 1}`"
                    role="tab"
                    :aria-selected="(tab === activeTab).toString()"
                    :aria-controls="`tab-panel-${index + 1}`"
            ></button>
        </template>
    </div>

    <div x-ref="tabs">
        {{ $slot }}
    </div>
</div>

@once
    @push('senna-ui-scripts')
    <script>
        function initTabs(activeTab) {
            return {
                tabs: [],
                tabHeadings: [],
                activeTab: activeTab,
                init() {
                    this.tabs = [...this.$refs.tabs.children];
                    this.tabHeadings = this.tabs.map((tab, index) => {
                        tab.__x.$data.id = (index + 1);
                        return tab.__x.$data.name;
                    });
                    console.log(activeTab)
                    if (!this.activeTab) {
                        this.activeTab = this.tabHeadings[0] ?? null;
                    }
                    this.toggleTabs();
                },
                toggleTabs() {
                    this.tabs.forEach(
                        tab => tab.__x.$data.showIfActive(this.activeTab)
                    );
                },
                tabClick(tab, $dispatch, $nextTick) {
                    this.activeTab = tab;
                    this.toggleTabs();
                    $el = this.$refs.tabs;
                    $nextTick(() => {
                        $dispatch('tab-visible', { tab, $el })
                    })
                }
            }
        }
    </script>
    @endpush
@endonce
