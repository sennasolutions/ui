<div class="w-full" x-data="initPageSearch()" x-init="init">
    <x-senna.input.search
        x-on:keydown.enter="onEnter"
        x-model="currentValue"
        x-on:input="search"
        placeholder="Search with ctrl+d or cmd+d"
        {{-- shortcut="cmd.d" --}}
        x-on:keydown.window.ctrl.d.prevent="onHotkey"
        x-on:keydown.window.cmd.d.prevent="onHotkey"
        iconClass="!w-6"
        class=" w-full"
        inputClass="!text-lg !pl-14 !pt-3" />

    <div x-ref="results" x-cloak x-on:click.away="show = false" x-show="show" class="overflow-y-auto max-h-80 absolute top-16 w-full max-w-3xl bg-white shadow">
        <template x-for="result in results" :key="result.name">
            <a class="block border-b hover:bg-gray-100 p-5" :href="result.href" x-on:click="clickResult(result)">
                <div x-text="result.name" class="font-semibold"></div>
                <div x-html="result.contents " class="opacity-40 truncate">

                </div>
            </a>
        </template>
    </div>
</div>

@once
    @push('senna-ui-scripts')
    <script>
        function initPageSearch() {
            return {
                currentValue: '',
                index: [],
                results: [],
                show: false,
                shownResult: 0,
                clickResult(result) {
                    this.shownResult = this.results.indexOf(result)
                    this.show = false
                },
                onHotkey() {
                    this.$refs.input.focus(); this.$refs.input.select()

                    // let nextResult = this.results.length > 0 ? this.results[this.shownResult+1 % this.results.length] : null

                    // if (nextResult) {
                    //     this.gotoResult(nextResult);
                    // }
                },
                gotoResult(result) {
                    window.location.hash = result.href;
                },
                onEnter() {
                    this.shownResult = 0;
                    this.$refs.results.querySelector("a").click();
                },
                init() {
                    document.querySelectorAll('[page-search]').forEach(x => {
                        let ps = x.attributes.getNamedItem('page-search')
                        let psn = x.attributes.getNamedItem('page-search-name')

                        this.index.push({
                            'href': ps.nodeValue,
                            'name': psn ? psn.nodeValue : '',
                            'contents': x.textContent.toLowerCase(),
                        })
                    })
                },
                search() {
                    if (this.currentValue.length == 0) {
                        this.results = []
                    } else {
                        let tokenized = this.currentValue.split(' ')
                        this.results = this.index.filter(x => {
                            let searchResult = true;

                            for (let index = 0; index < tokenized.length; index++) {
                                const token = tokenized[index];

                                if (x.name.indexOf(token) === -1 && x.contents.indexOf(token) === -1) {
                                    searchResult = false
                                }
                            }

                            return searchResult;
                        }).map(x => {
                            let clone = Object.assign({}, x);
                            let matchPosition = clone.contents.indexOf(this.currentValue)

                            clone.contents = clone.contents
                                .replace(this.currentValue, '<strong>' + this.currentValue + '</strong>')
                                .substr(Math.max(0, matchPosition-50), matchPosition+100+this.currentValue.length )

                            return clone
                        })
                    }
                    if (this.results.length > 0) {
                        this.show = true
                    }
                }
            }
        }
    </script>
    @endpush
@endonce
