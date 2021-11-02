@props([
    'options' => null
])

<div {{ $attributes }} x-data="initChart()"  x-json='@json($options)'>
   <div x-ref="chart">
   
   </div>
</div>

<script>
    function initChart() {
        return {
            init() {
                let options = JSON.parse(this.$el.getAttribute('x-json'))

                options = options ?? {
                    chart: {
                        type: 'line'
                    },
                    series: [{
                        name: 'sales',
                        data: [30,40,35,50,49,60,70,91,125]
                    }],
                    xaxis: {
                        categories: [1991,1992,1993,1994,1995,1996,1997, 1998,1999]
                    }
                }

                var chart = new ApexCharts(this.$refs.chart, options);

                chart.render();
            }
        }
    }
</script>