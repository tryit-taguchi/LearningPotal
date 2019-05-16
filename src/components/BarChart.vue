<script>
  import {HorizontalBar, mixins} from 'vue-chartjs'
  export default {
    extends: HorizontalBar,
    props: ['chartData', 'options'],
    mixins: [mixins.reactiveProp],
    mounted () {
      this.addPlugin({
        afterDatasetsDraw: (chart)=>{
          var ctx = chart.ctx;
          chart.data.datasets.forEach((dataset, i)=>{
            var meta = chart.getDatasetMeta(i);
            if (!meta.hidden) {
              meta.data.forEach((element, index)=>{
                ctx.font = Chart.helpers.fontString(12, 'normal', 'Helvetica Neue');
                ctx.fillStyle = '#666';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                var selectedId = chart.data.selectedId; // 選んだ選択肢
                if(
                  ( i === 0 && index === selectedId ) ||
                  ( i === 1 && index !== selectedId )
                ){
                  var dataString = dataset.sum[index].toString()+'人';
                  var position = element.tooltipPosition();
                  ctx.fillText(dataString, position.x+16, position.y);
                }
              });
            }
          });
        }
      });
      this.renderChart(this.chartData, this.options)
    }
  }
</script>