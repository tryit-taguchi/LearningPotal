<template>
  <bar-chart :width="width" :height="height" :chart-data="processedData" :options="options" :title="chartData.questionStr" :after-datasets-draw="afterDatasetsDraw" :style="style"></bar-chart>
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
      },
      afterDatasetsDraw: (chart)=>{
        var ctx = chart.ctx;
        chart.data.datasets.forEach(function(dataset, i) {
          // 非表示にするラベルはスキップ
          // if(_hideLabel&&_hideLabel.includes(i)){
          //   return false;
          // }
          // 「この会場」はスキップ
          if(i===0){
            return false;
          }
          var meta = chart.getDatasetMeta(i);
          if (!meta.hidden) {
            meta.data.forEach(function(element, index) {
              ctx.font = Chart.helpers.fontString(12, 'normal', 'Helvetica Neue');
              ctx.fillStyle = '#666';
              ctx.textAlign = 'right';
              ctx.textBaseline = 'middle';
              console.log(dataset)
              var dataString = dataset.data[index].toString()+'%';
              var position = element.tooltipPosition();
              if( position.x < 700 ) {
                ctx.fillText(dataString, position.x+30, position.y);
              } else {
                ctx.fillText(dataString, position.x-30, position.y);
              }
            });
          }
        });
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
            label: 'この会場',
            // data: this.chartData.siteValueList.map((v,i)=>(i!==this.chartData.selectedNo)?0:v),
            data: this.chartData.siteValueList,
            // sum: this.chartData.siteSumList,
            backgroundColor: 'rgba(255, 206, 86, 0.2)',
            borderColor: 'rgba(255, 206, 86, 1)',
            borderWidth: 2,
            stack: 'Stack 1'
          },{
            label: '全国平均',
            // data: this.chartData.totalValueList.map((v,i)=>(i===this.chartData.selectedNo)?0:v),
            data: this.chartData.totalValueList,
            // sum: this.chartData.totalSumList,
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderColor: 'rgba(255,99,132,1)',
            borderWidth: 2,
            stack: 'Stack 0'
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
  siteValueList: [25,75,0,0], // 会場 ％ (棒の長さに反映)
  siteSumList:[1,3,0,0], // 会場 合計 (棒の右に表示する数。票数とは独立)
  totalValueList: [25,75,0,0], // 全体 ％ (棒の長さに反映)
  totalSumList:[1,3,0,0], // 全体 合計 (棒の右に表示する数。票数とは独立)
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
