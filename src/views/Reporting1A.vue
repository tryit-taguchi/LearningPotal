<template>
  <div>
    <page-title>
      <template v-slot:left>試乗① 回答（レーダーチャート）<span  style="font-size:2.0em">1</span></template>
      新旧比較 現行 vs 新型
    </page-title>
    <div class="graph">
      <radar-chart-reporting :width="412" :height="450" :chart-data="chartData1"></radar-chart-reporting>
      <radar-chart-reporting :width="412" :height="450" :chart-data="chartData2"></radar-chart-reporting>
    </div>
  </div>
</template>

<script>
export default {
  // データ定義
  data: function(){
    return {
      // データの持たせ方は(仮)
      chartData1: {
        questionStr: 'あなた',
        answerList: [
          [ "全開加速" ],
          [ "操縦安定性" ],
          [ "乗り心地" ],
          [ "静粛性" ]
        ],
        currentValueList: [5,4,5,4],
        newmodelValueList: [4,5,4,5],
      },
      chartData2: {
        questionStr: '会場全体',
        answerList: [
          [ "全開加速" ],
          [ "操縦安定性" ],
          [ "乗り心地" ],
          [ "静粛性" ]
        ],
        currentValueList: [4,3,4,3],
        newmodelValueList: [3,4,3,4]
      }
    }
  }
}
</script>

<style scoped lang="scss">
.graph{
  display: flex;
  justify-content: center;
}
</style>