<template>
  <bar-chart :width="width" :height="height" :chart-data="processedData" :options="options" :title="chartData.questionStr" :style="style"></bar-chart>
</template>

<script>
export default {
  props: ['chartData', 'width', 'height'],
  data(){
    return{
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
      style: {
        backgroundColor: '#fff',
        width: this.width+'px',
        margin: '0 auto'
      }
    }
  },
  computed: {
    processedData: function(){
      console.log(this.chartData)
      return {
        labels: this.chartData.answerList,
        datasets: [
          {
            label: 'あなたの回答',
            data: this.chartData.valueList.map((v,i)=>(i!==this.chartData.selected-1)?0:v),
            sum: this.chartData.sumList,
            backgroundColor: 'rgba(86, 206, 255, 0.2)',
            borderColor: 'rgba(86, 206, 255, 1)',
            borderWidth: 2,
            stack: 'Stack 1'
          },{
            label: 'この会場',
            data: this.chartData.valueList.map((v,i)=>(i===this.chartData.selected-1)?0:v),
            sum: this.chartData.sumList,
            backgroundColor: 'rgba(255, 206, 86, 0.2)',
            borderColor: 'rgba(255, 206, 86, 1)',
            borderWidth: 2,
            stack: 'Stack 1'
          }
        ],
        selectedId: this.chartData.selected-1 // 「あなたの回答」判別のために加えた独自のプロパティ
      }
    },
  }
}
</script>

<style lang="scss">

</style>
