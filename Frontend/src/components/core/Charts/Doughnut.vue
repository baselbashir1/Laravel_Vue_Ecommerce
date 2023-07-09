<script>
import { defineComponent, h } from 'vue';
import { Doughnut } from 'vue-chartjs';
import { Chart as ChartJs, Title, Tooltip, Legend, ArcElement, CategoryScale } from 'chart.js';

ChartJs.register(Title, Tooltip, Legend, ArcElement, CategoryScale);

export default defineComponent({
    name: 'DoughnutChart',
    components: {
        Doughnut
    },
    props: {
        chartId: {
            type: String,
            default: 'doughnut-chart'
        },
        width: {
            type: Number,
            default: 400
        },
        height: {
            type: Number,
            default: 400
        },
        cssClasses: {
            default: '',
            type: String
        },
        plugins: {
            type: Array,
            default: () => []
        },
        data: {
            type: Object,
            required: true
        }
    },
    setup(props) {
        const chartOptions = {
            responsive: true,
            maintainAspectRation: false
        }

        return () => h(Doughnut, {
            chartData: props.data,
            chartOptions,
            chartId: props.chartId,
            width: props.width,
            height: props.height,
            cssClasses: props.cssClasses,
            styles: props.styles,
            plugins: props.plugins
        });
    }
});

</script>

<style scoped></style>