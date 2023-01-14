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

            window.euro = (value, digits = 0) => {
                // 1000.00 => 1.000,00
                return '€' + value.toLocaleString('nl-NL', { minimumFractionDigits: digits, maximumFractionDigits: digits })
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

                Apex.chart = {
                    locales: [
                        {
                            'name': 'nl',
                            'options': {
                                'months': [
                                    'Januari',
                                    'Februari',
                                    'Maart',
                                    'April',
                                    'Mei',
                                    'Juni',
                                    'Juli',
                                    'Augustus',
                                    'September',
                                    'Oktober',
                                    'November',
                                    'December'
                                ],
                                'shortMonths': [
                                    'Jan',
                                    'Feb',
                                    'Mrt',
                                    'Apr',
                                    'Mei',
                                    'Jun',
                                    'Jul',
                                    'Aug',
                                    'Sep',
                                    'Okt',
                                    'Nov',
                                    'Dec'
                                ],
                                'days': [
                                    'Zondag',
                                    'Maandag',
                                    'Dinsdag',
                                    'Woensdag',
                                    'Donderdag',
                                    'Vrijdag',
                                    'Zaterdag'
                                ],
                                'shortDays': ['Zo', 'Ma', 'Di', 'Wo', 'Do', 'Vr', 'Za'],
                                'toolbar': {
                                    'exportToSVG': 'Download SVG',
                                    'exportToPNG': 'Download PNG',
                                    'exportToCSV': 'Download CSV',
                                    'menu': 'Menu',
                                    'selection': 'Selectie',
                                    'selectionZoom': 'Zoom selectie',
                                    'zoomIn': 'Zoom in',
                                    'zoomOut': 'Zoom out',
                                    'pan': 'Verplaatsen',
                                    'reset': 'Standaardwaarden'
                                }
                            }
                        }],
                        defaultLocale: 'nl'
                }

                this.$nextTick(() => {
                    this.chart.render()
                    resolve()
                })
            })
        },
        get options() {
            let apex = this.apex ?? '{}';
            
            if (typeof apex === 'string') {
                apex = Function('return ' + this.apex ?? '{}')()
            }

            let conf = Object.assign({}, apex)

            let getDataPoint = (dataPointIndex, seriesIndex = 0) => {
                let flatSeries = conf.chart.type == 'pie';
                let data = flatSeries ? conf.series : conf.series[seriesIndex].data;

                return data[dataPointIndex] ?? null;
            }

            return {
                chart: { 
                    type: 'bar', 
                    toolbar: false, 
                    height: '100%',
                },
                ...conf,
                ...{
                    tooltip: {
                        marker: false,
                        ...conf.tooltip ?? {},
                        y: {
                            formatter: (x, { seriesIndex, dataPointIndex, w }) => {
                                let flatSeries = conf.chart.type == 'pie';

                                let formatter = flatSeries ? conf.insight.formatters : conf.insight.formatters[seriesIndex] ?? null;
                                let series = conf.series

                                let map = {};

                                if (!flatSeries) {
                                    series.forEach((serie) => {
                                        map[serie.name] = serie.data[dataPointIndex] ?? null;
                                    })
                                }

                                if (formatter) {
                                    return new Function('x', 'data', 'return ' + formatter)(x, map)
                                }

                                return x;
                            }
                        }
                    },
                    chart: {
                        ...conf.chart ?? {},
                        events: {
                            click: (event, chartContext, { seriesIndex, dataPointIndex }) => {
                                if (this.$wire) {
                                    this.$wire.chartClick({
                                        dataPointIndex,
                                        seriesIndex,
                                    })
                                }
                            },
                        }
                    }
                }
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
