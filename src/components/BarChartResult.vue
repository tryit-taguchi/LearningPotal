<template><!-- 「全国平均」ありの棒グラフ／タイトルなし -->
  <div :style="{width:width+'px',height:height+'px',color:'#000',backgroundColor:'#fff',margin:'10px auto 0'}">
    <apexchart ref="chart" type="bar" :width="width" :height="height" :options="apexChartOptions" :series="apexChartSeries" />
  </div>
</template>

<script>
export default {
  props: {
    chartData: {},
    width: {},
    height: {},
    showYourSelect: Boolean
  },
  data(){
    return{
    }
  },
  mounted() {
    this.addYourSelect()
  },
  computed: {
    apexChartOptions: function(){
      return {
        plotOptions: {
          bar: {
            horizontal: true,
            dataLabels: {
              position: 'top'
            }
          }
        },
        chart: {
          toolbar: {
            show: false
          },
          events: {
            updated: (chartContext, config)=>{
              this.addYourSelect()
            }
          }
        },
        labels: this.chartData.answerList,
        title: {
          text: undefined,
          // text: this.chartData.questionStr,
          align: 'center',
          style: {
            fontSize: '20px',
            color: '#000'
          }
        },
        legend: {
          showForSingleSeries: true,
          position: 'top',
          onItemClick: {
              toggleDataSeries: false
          },
          onItemHover: {
              highlightDataSeries: false
          },
        },
        dataLabels: {
          formatter: (val, opts)=>{
            return val+'%'
          },
          offsetX: -14,
          offsetY: 0,
          style: {
            colors: ['#666']
          }
        },
        xaxis: {
          labels: {
            show: false,
          },
          axisBorder: {
            show: false,
          },
          axisTicks: {
            show: false,
          },
          crosshairs: {
            show: false,
          },
          // 更新後にmaxが無視される挙動の解決策が見つからないためmaxの指定は削除
          // max: Math.max(...this.chartData.totalValueList.map((v)=>parseInt(v))),
        },
        fill: {
          colors: ['rgba(255, 206, 86, 0.2)','rgba(255, 99, 132, 0.2)']
        },
        stroke: {
          width: 2,
          colors: ['rgba(255, 206, 86, 1)','rgba(255,99,132,1)']
        },
        colors: ['rgba(255, 206, 86, 1)','rgba(255,99,132,1)']
      }
    },
    apexChartSeries: function(){
      return [
        {
          name: 'この会場',
          data: this.chartData.siteValueList
        },
        {
          name: '全国平均',
          data: this.chartData.totalValueList
        }
      ]
    },
  },
  methods: {
    addYourSelect: function(){
      if(this.showYourSelect){
        // legend を無理矢理追加
        // option と series の更新で2回呼ばれてしまうためここで数を見る
        const legendCount = [...this.$refs.chart.$el.querySelectorAll('.apexcharts-legend-series')].length
        if(legendCount===3) return false
        const legends = this.$refs.chart.$el.querySelector('.apexcharts-legend')
        const legend = this.$refs.chart.$el.querySelector('.apexcharts-legend-series').cloneNode(true)
        legend.querySelector('.apexcharts-legend-marker').style.background = 'rgba(86, 206, 255, 1)'
        legend.querySelector('.apexcharts-legend-marker').style.borderColor = 'rgba(86, 206, 255, 1)'
        legend.querySelector('.apexcharts-legend-text').innerText = 'あなたの回答'
        legend.setAttribute('rel', 3)
        legend.querySelector('.apexcharts-legend-marker').setAttribute('rel', 3)
        legend.querySelector('.apexcharts-legend-text').setAttribute('rel', 3)
        legends.appendChild(legend)
        // path を無理矢理変更
        this.chartData.selectedNoList.forEach((v)=>{
          const path = this.$refs.chart.$el.querySelectorAll('.apexcharts-series[rel="1"] path')[v]
          path.setAttribute("fill","rgba(86, 206, 255, 0.2)")
          path.setAttribute("stroke","rgba(86, 206, 255, 1)")
        })
      }
    }
  }
}
</script>

<style lang="scss">

</style>
