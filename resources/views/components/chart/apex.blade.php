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
                    getDatapoint(conf, dataPointIndex, seriesIndex = 0) {
                        let flatSeries = conf.chart.type == 'pie';
                        // let serie = flatSeries ? conf.insight.seriesWithHiddenData : conf.insight.seriesWithHiddenData[seriesIndex];
                        let serie = flatSeries ? conf.insight.seriesWithHiddenData[0] : conf.insight.seriesWithHiddenData[seriesIndex];
                        let data = serie.data;
                        let dataPoint = data[dataPointIndex] ?? null;

                        let otherDataPointsAsObject = flatSeries ? {} : conf.insight.seriesWithHiddenData.reduce((acc, serie) => {
                            acc[serie.name] = serie.data[dataPointIndex]
                            return acc
                        }, {})

                        return {
                            name: serie.name ?? null,
                            ...otherDataPointsAsObject,
                            ...dataPoint,
                            value: typeof dataPoint === 'object' ? (dataPoint.y ?? dataPoint.x)  : dataPoint,
                            toString() { return this.value}
                        }
                    },
                    tooltipFormatter(x, obj) {
                        return this.handleFormatter('hover', this.getDatapoint(this.apexParsed, obj.dataPointIndex, obj.seriesIndex))
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
                                            let popupFormatter = conf.insight?.rawFormatters?.popup ?? null

                                            if (popupFormatter) {
                                                this.handlePopup(this.getDatapoint(conf, dataPointIndex, seriesIndex))
                                            }
                                        },
                                    }
                                }
                            }
                        }

                        return config;
                    },
                    handlePopup(data) {
                        let element = document.createElement('div');
                        element.className = 'fixed border bg-white overflow-y-auto p-5 rounded-lg shadow-2xl z-50'
                        element.innerHTML = this.handleFormatter('popup', data)
                        element.style.top = '50%'
                        element.style.left = '50%'
                        element.style.transform = 'translate(-50%, -50%)'
                        element.style.maxWidth = '650px'
                        element.style.maxHeight = '380px'
                        element.style.width = '100%'
                        element.style.height = '100%'

                        document.body.appendChild(element)
                        
                        // once
                        setTimeout(() => {
                            document.body.addEventListener('click', function handler() {
                                element.remove()
                                document.body.removeEventListener('click', handler)
                            })
                        }, 0)
                    },
                    handleFormatter(type = 'popup', data) {
                        let conf = JSON.parse(JSON.stringify(this.apexParsed))
                        let formatter = conf.insight?.rawFormatters[type] ?? 'x';

                        let helpers = this.setupHelpers({})
                        let prepend = Object.keys(helpers).map((key) => {
                            return `var ${key} = ${helpers[key].toString()};`
                        }).join('')

                        return new Function('x', prepend + 'return \'\' + ' + formatter)(data)
                    },
                    setupHelpers(obj = {}) {
                        obj.percentage = (value) => {
                            value = parseFloat(value.toString())
                            return Math.round((value) * 100) + '%'
                        }

                        obj.euro = (value, digits = 0) => {
                            value = parseFloat(value.toString())
                            return '€' + value.toLocaleString('nl-NL', { minimumFractionDigits: digits, maximumFractionDigits: digits })
                        }

                        obj.dollar = (value) => {
                            value = parseFloat(value.toString())
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
