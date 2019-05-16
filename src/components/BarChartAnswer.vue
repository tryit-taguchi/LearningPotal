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
      //console.log(this.chartData)
      return {
        labels: this.chartData.answerList,
        datasets: [
          {
            label: 'あなたの回答',
            data: this.chartData.valueList.map((v,i)=>(i!==this.chartData.selectedNo)?0:v),
            sum: this.chartData.sumList,
            backgroundColor: 'rgba(86, 206, 255, 0.2)',
            borderColor: 'rgba(86, 206, 255, 1)',
            borderWidth: 2,
            stack: 'Stack 1'
          },{
            label: 'この会場',
            data: this.chartData.valueList.map((v,i)=>(i===this.chartData.selectedNo)?0:v),
            sum: this.chartData.sumList,
            backgroundColor: 'rgba(255, 206, 86, 0.2)',
            borderColor: 'rgba(255, 206, 86, 1)',
            borderWidth: 2,
            stack: 'Stack 1'
          }
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
  valueList: [25,75,0,0], // 票数 (棒の長さに反映)
  sumList:[1,3,0,0], // 合計 (棒の右に表示する数。票数とは独立)
  selectedNo: 1 // (単一選択) 「あなたの回答」この選択肢の棒は色が変わる
  selectedNoList: [1,2]  // (複数選択) 「あなたの回答」この選択肢の棒は色が変わる
}

TODO：複数選択の場合の処理はまだ未実装

備忘録(書く場所がないので)

他の棒グラフのパターン
(コンポーネントを分けたほうが建設的だと思うもの)
・「あなたの回答(青)」「この会場(黄)」「全国平均(赤)」のパターン(Question10のAnswerページに使われていた)
・「この会場(黄)」「全国平均(赤)」のパターン(結果まとめページ)

-->
