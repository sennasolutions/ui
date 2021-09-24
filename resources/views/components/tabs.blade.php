@props([
    'active',
    'tabClasses' => '',
    'value' => null
])

@php
if (isset($active)) $value = $active;
@endphp

<div
    data-sn='tabs'
    {{ $attributes->merge(['class' => 'w-full'])->only('class') }}
    x-data="initTabs(@safe_entangle($attributes->wire('model')) )"
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
                    class='transition duration-50 ease-in-out -mb-px px-4 uppercase tracking-wide text-gray-900 text-sm font-bold rounded-none py-2 focus:outline-none {{ $tabClasses }}'
                    {{ $attributes->except('class') }}
                    :class="tab === activeTab ? 'active border-b-2 border-sui-first' : 'text-gray-400'"
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

                    this.$nextTick(() => {
                        this.tabs = [...this.$refs.tabs.children];
                        this.tabHeadings = this.getTabs().map((tab, index) => {
                            tab._x_dataStack[0].id = (index + 1);
                            // tab.__x.$data.id = (index + 1);
                            return tab._x_dataStack[0].name;
                        });
                        if (!this.activeTab) {
                            this.activeTab = this.tabHeadings[0] ?? null;
                        }
                        this.toggleTabs();
                    })
                },
                getTabs() {
                    return this.tabs.filter(x => typeof x._x_dataStack !== 'undefined');
                },
                toggleTabs() {
                    this.getTabs().forEach(
                        tab => tab._x_dataStack[0].showIfActive(this.activeTab)
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
