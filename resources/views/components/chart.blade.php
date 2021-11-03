@props([
    'options' => null,
    'bottom' => null,
])

<div x-data="initChart()" x-json='@json([
        'options'=> $options,
        'bottom' => $bottom
    ])'>
    <div {{ $attributes }} x-ref="main"></div>
    <div x-ref="bottom"></div>
</div>

<script>
    function initChart() {
        return {
            init() {
                let json = JSON.parse(this.$el.getAttribute('x-json'))
                
                let data = [{
                        x: new Date(1538778600000),
                        y: [6629.81, 6650.5, 6623.04, 6633.33]
                    },
                    {
                        x: new Date(1538780400000),
                        y: [6632.01, 6643.59, 6620, 6630.11]
                    },
                    {
                        x: new Date(1538782200000),
                        y: [6630.71, 6648.95, 6623.34, 6635.65]
                    },
                    {
                        x: new Date(1538784000000),
                        y: [6635.65, 6651, 6629.67, 6638.24]
                    },
                ];

                let jsonOptions = json.options ?? {}

                var options = {
                    series: [
                        {
                            name: 'volume2',
                            type: 'line',
                            data: data.map(function(item, i) {
                                return [item.x, item.y[3]];
                            })
                        },
                        {
                            name: 'volume3',
                            type: 'bar',
                            data: data.map(function(item, i) {
                                return [item.x, item.y[2]];
                            })
                        },
                    ],
                    chart: {
                        height: 290,
                        id: 'main',
                        animations: {
                            enabled: false,
                        },
                        toolbar: {
                            autoSelected: 'pan',
                            show: false
                        },
                        zoom: {
                            enabled: false
                        },
                    },
                    plotOptions: {
                        candlestick: {
                            colors: {
                                upward: '#3C90EB',
                                downward: '#DF7D46'
                            }
                        }
                    },
                    xaxis: {
                        type: 'datetime'
                    },
                    ...jsonOptions
                };

                var chart = new ApexCharts(this.$refs.main, options);
                chart.render();

                let jsonBottom = json.bottom ?? {}

                var optionsBar = {
                    series: [
                        {
                            name: 'volume3',
                            type: 'bar',
                            data: data.map(function(item, i) {
                                return [item.x, item.y[3]];
                            })
                        },
                    ],
                    chart: {
                        height: 250,
                        stacked: false,
                        animations:
                        {
                            enabled: false,
                        },
                        brush: {
                            enabled: true,
                            target: 'main'
                        },
                        selection: {
                            enabled: true,
                            xaxis: {
                                // min: new Date('20 Jan 2017').getTime(),
                                // max: new Date('10 Dec 2017').getTime()
                            },
                            fill: {
                                color: '#ccc',
                                opacity: 0.4
                            },
                            stroke: {
                                color: '#0D47A1',
                            }
                        },
                    },
                    dataLabels: {
                        enabled: false
                    },
                    plotOptions: {
                        bar: {
                            columnWidth: '80%',
                            colors: {
                                ranges: [{
                                    from: -1000,
                                    to: 0,
                                    color: '#F15B46'
                                }, {
                                    from: 1,
                                    to: 10000,
                                    color: '#FEB019'
                                }],

                            },
                        }
                    },
                    stroke: {
                        width: [2, 2, 2],
                        curve: 'smooth'
                    },
                    xaxis: {
                        type: 'datetime',
                        axisBorder: {
                            offsetX: 13
                        }
                    },
                    yaxis: {
                        labels: {
                            show: true
                        }
                    },
                    ...jsonBottom
                };

                if (json.bottom ?? null) {
                    var chartBar = new ApexCharts(this.$refs.bottom, optionsBar);
                    chartBar.render();
                }

            }
        }
    }
</script>
