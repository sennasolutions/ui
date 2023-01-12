@props([
    'apex' => null,
])

@wireProps

<div
    {{ $attributes }}
    
    x-data="{
        apex: @entangleProp('apex'),
        initialized: false,
        chart: null,
        async init() {
            this.setupHelpers()
            this.$watch('apex', async () => {
                await this.initChart()
                this.chart.updateOptions(this.options)
            })

            if (this.apex) {
                this.initChart()
            }
        },
        setupHelpers() {
            window.percentage = (ratio) => {
                return Math.round((ratio) * 100) + '%'
            }

            window.euro = (value) => {
                return '€' + value
            }

            window.dollar = (value) => {
                return '$' + value
            }
        },
        async initChart() {
            if (this.initialized) return

            return new Promise((resolve) => {
                this.initialized = true

                this.chart = new ApexCharts(this.$refs.chart, this.options)

                this.$nextTick(() => {
                    this.chart.render()
                    resolve()
                })
            })
        },
        get options() {
            let apex = this.apex ?? '{}';
            if (typeof apex === 'string') {
                apex = Function('return ' + this.apex)()
            }

            console.log(apex)

            return {
                chart: { type: 'bar', toolbar: false, height: '100%' },
                tooltip: {
                    marker: false,
                    x: {
                        format: 'dd/MM/yyyy'
                    },
                    y: {
                        formatter(x, { seriesIndex, dataPointIndex, w }) {
                            let formatter = apex.series[seriesIndex].formatter
                            let map = {};

                            if (apex.series[0].data ?? null) {
                                for (let i = 0; i < apex.series.length; i++) {
                                    map[apex.series[i].name] = apex.series[i].data[dataPointIndex];
                                }
                            }

                            if (formatter) {
                                return new Function('x', 'data', 'return ' + formatter)(x, map)
                            }

                            return x;
                        }
                    }
                },
                {{-- yaxis: { 
                    labels: {
                        formatter: function (value) {
                            return value + '$'
                        }
                    },
                }, --}}
                ...apex
            }
        }
    }"
    class="w-full"
>
    <div wire:ignore x-ref="chart"></div>
</div>


@once
    @push('senna-ui-scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    @endpush
@endonce
