<script>
  import {HorizontalBar, mixins} from 'vue-chartjs'
  export default {
    extends: HorizontalBar,
    props: ['chartData'],
    mixins: [mixins.reactiveProp],
    data () {
      return {
        // datacollection: {
        //   labels: this.props.answerList,
        //   datasets: [
        //     {
        //       label: 'あなたの回答',
        //       data: this.props.valueList.map((v,i)=>(i!==this.props.selected-1)?0:v),
        //       sum: this.props.sumList,
        //       backgroundColor: 'rgba(86, 206, 255, 0.2)',
        //       borderColor: 'rgba(86, 206, 255, 1)',
        //       borderWidth: 2,
        //       stack: 'Stack 1'
        //     },{
        //       label: 'この会場',
        //       data: this.props.valueList.map((v,i)=>(i===this.props.selected-1)?0:v),
        //       sum: this.props.sumList,
        //       backgroundColor: 'rgba(255, 206, 86, 0.2)',
        //       borderColor: 'rgba(255, 206, 86, 1)',
        //       borderWidth: 2,
        //       stack: 'Stack 1'
        //     }
        //   ],
        //   selectedId: this.props.selected-1 // 「あなたの回答」判別のために加えた独自のプロパティ
        // },

        options: {
          layout: {
            padding: {
              right: 30
            }
          },
          animation: {
            duration: 0
          },
          scales: {
            xAxes: [{
              ticks: {
                display: false
              },
              gridLines: {
                drawBorder: false,
              }
            }],
            yAxes: [{
              ticks: {
                fontSize: 12
              },
              gridLines: {
                display:false
              },
            }]
          },
          legend: {
            labels: {
              fontSize: 12
            }
          },
          tooltips: {
            enabled: false
          },
          title: {
            display: true,
            fontSize: 24,
            text: this.chartData.questionStr
          },
          maintainAspectRatio:false
        },
      }
    },
    mounted () {
      this.addPlugin({
        afterDatasetsDraw: function(chart) {
          var ctx = chart.ctx;
          chart.data.datasets.forEach(function(dataset, i) {
            var meta = chart.getDatasetMeta(i);
            if (!meta.hidden) {
              meta.data.forEach(function(element, index) {
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