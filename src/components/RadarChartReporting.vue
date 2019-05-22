<template>
  <radar-chart :width="width" :height="height" :chart-data="processedData" :options="options" :title="chartData.questionStr" :style="style"></radar-chart>
</template>

<script>
export default {
  props: ['chartData', 'width', 'height'],
  data(){
    return{
      options: {
        animation: {
          duration: 0
        },
        scale: {
          ticks: {
            beginAtZero:true,
            stepSize: 1,
            max: 5,
            fontSize: 12
          },
          pointLabels: {
            fontSize: 12
          }
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
        margin: '0'
      }
    }
  },
  computed: {
    processedData: function(){
      return {
        labels: this.chartData.answerList,
        datasets: [
          {
            label: '現行',
            data: this.chartData.currentValueList,
            backgroundColor: 'rgba(255, 206, 86, 0.2)',
            fill:false,
            borderColor: 'rgba(255, 206, 86, 1)',
            borderWidth: 2,
          },
          {
            label: '新型',
            data: this.chartData.newmodelValueList,
            backgroundColor: 'rgba( 86, 206,255, 0.2)',
            fill:false,
            borderColor: 'rgba( 86, 206,255, 1)',
            borderWidth: 2,
          },
        ],
        selectedId: this.chartData.selectedNo // 「あなたの回答」判別のために加えた独自のプロパティ
      }
    },
  }
}
</script>

<style lang="scss">

</style>

<!--

'chartData' に渡されるオブジェクトの形式

chartData: {
  questionStr: "設問のタイトル",
  answerList: ["選択肢1","選択肢2","選択肢3","選択肢4"],
  dataset: {
  }, // 会場 ％ (棒の長さに反映)
}

-->
