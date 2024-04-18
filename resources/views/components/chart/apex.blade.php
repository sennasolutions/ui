@props([
    'apex' => null,
])

@wireProps

<div
    {{ $attributes }}
    x-data="apex_chart({
        apexConfig: @entangleProp('apex')
    })"
    class="w-full"
>
    <div wire:ignore x-ref="chart" class="grow"></div>
</div>

@once
    @push('senna-ui-scripts')
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

        <script>

            document.addEventListener('alpine:init', () => {
                Alpine.data('apex_chart', (props) => ({
                    apex: props.apexConfig,
                    apexParsed: null,
                    initialized: false,
                    chart: null,
                    async init() {
                        if (this.apex) {
                            this.initChart()
                        }

                        this.$watch('apex', async () => {
                            await this.initChart()
                            this.parseApex();
                            this.chart.updateOptions(this.options)
                        })
                    },
                    async initChart() {
                        if (this.initialized) return
                        
                        return new Promise((resolve) => {
                            this.initialized = true
                            this.parseApex();

                            this.chart = new ApexCharts(this.$refs.chart, this.options)

                            Apex.chart = {
                                locales: [apexNL],
                                defaultLocale: 'nl'
                            }

                            this.$nextTick(() => {
                                this.chart.render()
                                resolve()
                            })
                        })
                    },
                    parseApex() {
                        let apex = this.apex ?? '{}';
                        
                        if (typeof apex === 'string') {
                            // Parse the string
                            apex = Function('return ' + this.apex ?? '{}')()
                        }

                        this.apexParsed = Object.assign({}, apex)
                    },
                    getDatapoint(conf, index, seriesIndex = 0) {
                        let flatSeries = conf.chart.type == 'pie';
                        let data = flatSeries ? conf.series : conf.series[seriesIndex].data;

                        return data[dataPointIndex] ?? null;
                    },
                    tooltipFormatter(x, obj) {
                        let seriesIndex = obj?.seriesIndex ?? 0;
                        let dataPointIndex = obj?.dataPointIndex ?? 0;
                        let w = obj?.w ?? null;
                        let conf = this.apexParsed
                        let flatSeries = conf.chart.type == 'pie';
                        let helpers = this.setupHelpers({})

                        let formatter = flatSeries ? conf.insight?.formatters : conf.insight?.formatters[seriesIndex] ?? null;
                        formatter = formatter ?? conf.insight?.raw_formatters?.popover ?? null;

                        let series = conf.series


                        function isNumeric(n) {
                            return !isNaN(parseFloat(n)) && isFinite(n);
                        }

                        // Lets the user write 
                        let xObject = {
                            x: x,
                            toString() {
                                return this.x
                            }
                        };

                        if (!flatSeries) {
                            series.forEach((serie) => {
                                let data =  serie.data[dataPointIndex] ?? null

                                if (isNumeric(data)) {
                                    data = Number(data)
                                }

                                xObject[serie.name] = data
                            })

                            let serie = series[seriesIndex]

                            xObject.current = serie.data[dataPointIndex] ?? null
                        }

                        if (formatter) {
                            let prepend = '';

                            for (let key in helpers) {
                                prepend += `var ${key} = ${helpers[key].toString()};`
                            }

                            {{-- console.log(obj, xObject) --}}

                            return new Function('x', prepend + 'return \'\' + ' + formatter)(xObject)
                        }

                        if (!formatter && conf.chart.type == 'pie') {
                            // append the percentage
                            let total = series.reduce((a, b) => a + b, 0)
                            let percentage = x / total

                            return `${x} (${helpers.percentage(percentage)})`
                        }

                        return xObject
                    },
                    onChartClick(event, chartContext, { seriesIndex, dataPointIndex }) {
                        if (this.$wire) {
                            this.$wire.chartClick({
                                dataPointIndex,
                                seriesIndex,
                            })
                        }
                    },
                    get options() {
                        let conf = JSON.parse(JSON.stringify(this.apexParsed))

                        let config = {
                            grid: {
                                borderColor: 'rgba(0,0,0,0.035)',
                            },
                            chart: { 
                                type: 'bar', 
                                toolbar: false, 
                                height: '100%',
                            },
                            // forecastDataPoints: {
                            //      count: 10,
                            // },
                            ...conf,
                            ...{
                                tooltip: {
                                    marker: false,
                                    ...conf.tooltip ?? {},
                                    intersect: false,
                                    shared: true,
                                    followCursor: true,
                                    y: {
                                        formatter: (value, obj) => {
                                            return this.tooltipFormatter(value, obj)
                                        }
                                    }
                                },
                                chart: {
                                    ...conf.chart ?? {},
                                    events: {
                                        click: (event, chartContext, { seriesIndex, dataPointIndex }) => {
                                            // this.onChartClick(event, chartContext, { seriesIndex, dataPointIndex })
                                        },
                                    }
                                }
                            }
                        }

                        return config;
                    },
                    setupHelpers(obj = {}) {
                        obj.percentage = (ratio) => {
                            return Math.round((ratio) * 100) + '%'
                        }

                        obj.euro = (value, digits = 0) => {
                            // 1000.00 => 1.000,00
                            return '€' + value.toLocaleString('nl-NL', { minimumFractionDigits: digits, maximumFractionDigits: digits })
                        }

                        obj.dollar = (value) => {
                            return '$' + value
                        }

                        obj.isNumeric = (n) => {
                            return !isNaN(parseFloat(n)) && isFinite(n);
                        }

                        return obj
                    },
                })
            )
            })

            let apexNL = {
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
            }
        </script>
    @endpush
@endonce
