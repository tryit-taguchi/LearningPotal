<script>
  import {HorizontalBar, mixins} from 'vue-chartjs'
  export default {
    extends: HorizontalBar,
    props: ['chartData', 'options', 'afterDatasetsDraw'],
    mixins: [mixins.reactiveProp],
    mounted () {
      this.addPlugin({
        afterDatasetsDraw: this.afterDatasetsDraw
      });
      this.renderChart(this.chartData, this.options)
    }
  }
</script>