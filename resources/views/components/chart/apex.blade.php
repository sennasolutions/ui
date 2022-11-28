@props([
    'values' => [45, 55, 75, 25, 45, 110],
    'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'June'],
    'apex' => [],
])

@wireProps

<div
    {{ $attributes }}
    x-data="{
        values: @entangleProp('values'),
        labels: @entangleProp('labels'),
        apex: @entangleProp('apex'),
        init() {
            let chart = new ApexCharts(this.$refs.chart, this.options)

            chart.render()

            this.$watch('values', () => {
                chart.updateOptions(this.options)
            })
            this.$watch('labels', () => {
                chart.updateOptions(this.options)
            })
            this.$watch('apex', () => {
                chart.updateOptions(this.options)
            })
        },
        get options() {
            return {
                chart: { type: 'bar', toolbar: false, height: '100%' },
                tooltip: {
                    marker: false,
                    y: {
                        formatter(number) {
                            return '$'+number
                        }
                    }
                },
                xaxis: { categories: this.labels },
                series: [{
                    name: 'Sales',
                    data: this.values,
                }],
                ...this.apex
            }
        }
    }"
    class="w-full"
>
    <div x-ref="chart"></div>
</div>


@once
    @push('senna-ui-scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    @endpush
@endonce
