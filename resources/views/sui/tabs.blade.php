@php
/**
 * @name Tabs
 * @description Tabs component
 */
@endphp

@props([
    /**
     * @param string active The name of the active tab
     */
    'active',
])

@wireProps

<div
    {{ $attributes->root()->merge(['class' => 'w-full']) }}
    x-data="sennaTabs({ activeTab: @entangleProp('active') })"
>
    <div class="mb-3 border-b"
         role="tablist"
    >
        <template x-for="(tab, index) in tabHeadings"
            :key="index"
        >
            <button
                {{ 
                    $attributes->namespace('tab')->merge([
                        'class' => 'transition duration-50 ease-in-out -mb-px px-4 uppercase tracking-wide text-gray-900 text-sm font-bold rounded-none py-2 focus:outline-none',
                        'type' => 'button',
                        'role' => 'tab',
                    ]) 
                }}
                x-text="tab"
                x-on:click="tabClick(tab, $dispatch, $nextTick)"
                :class="tab === activeTab ? 'active border-b-2 border-primary' : 'text-gray-400'"
                :id="`tab-${index + 1}`"
                :aria-selected="(tab === activeTab).toString()"
                :aria-controls="`tab-panel-${index + 1}`"
            ></button>
        </template>
    </div>

    <div x-ref="tabs" {{ $attributes->namespace('tabs') }}>
        {{ $slot }}
    </div>
</div>

@once
    @push('senna-ui-scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('sennaTabs', ({ activeTab }) => ({
                    tabs: [],
                    tabHeadings: [],
                    activeTab,
                    init() {
                        this.$nextTick(() => {
                            this.tabs = [...this.$refs.tabs.querySelectorAll('.sui-tab')]
                                .filter(x => x.parentElement.getAttribute('visible') != 'no')
                            this.tabHeadings = this.getTabs().map((tab, index) => {
                                tab._x_dataStack[0].id = (index + 1);
                                // tab.__x.$data.id = (index + 1);
                                return tab._x_dataStack[0].name;
                            }) ?? [];
                            if (!this.activeTab) {
                                this.activeTab = this.tabHeadings[0] ?? null;
                            }
                            console.log(this.tabs, [...this.$refs.tabs.querySelectorAll('.sui-tab')])
                            this.toggleTabs();
                        })

                        // Check the localstorage for an active tab for this page
                        let urlPath = window.location.pathname;
                        let activeTab = localStorage.getItem('activeTab-' + urlPath);

                        if (activeTab) {
                            this.activeTab = activeTab;
                            let $el = this.$refs.tabs;
                            this.$nextTick(() => {
                                $dispatch('tab-visible', { activeTab, $el })
                            })
                        }
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

                        // Save the active tab to localstorage
                        let urlPath = window.location.pathname;
                        localStorage.setItem('activeTab-' + urlPath, tab);

                        let $el = this.$refs.tabs;

                        $nextTick(() => {
                            $dispatch('tab-visible', { tab, $el })
                        })
                    }
                }))
            })
        </script>
    @endpush
@endonce