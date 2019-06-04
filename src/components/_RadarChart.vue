<script>
  import {Radar, mixins} from 'vue-chartjs'
  export default {
    extends: Radar,
    props: ['chartData', 'options'],
    mixins: [mixins.reactiveProp],
    mounted () {
      this.renderChart(this.chartData, this.options)
    }
  }
</script>