<template>
	<div :style="{width:width+'px',height:height+'px',color:'#000',backgroundColor:'#fff'}">
		<apexchart type="radar" :width="width" :height="height" :options="apexChartRadarOptions" :series="apexChartRadarSeries" />
	</div>
</template>

<script>
export default {
	props: ['chartData', 'width', 'height'],
	data(){
		return{
		}
	},
	computed: {
		apexChartRadarOptions: function(){
			return {
				chart: {
					toolbar: {
						show: false
					},
				},
				labels: this.chartData.answerList,
				title: {
						text: this.chartData.questionStr,
						align: 'center',
						style: {
							fontSize: '20px',
							color: '#000'
						}
				},
				yaxis: {
					tickAmount: 5,
					min:0,
					max:5,
				},
				legend: {
					position: 'top',
				},
				fill: {
					colors: this.chartData.backgroundColor.map((val)=>val.replace(/\s{2,}/g,' ')), // 連続した半角スペースが含まれているとパースの不具合で描画が崩れるための処置
				},
				stroke: {
					colors: this.chartData.borderColor.map((val)=>val.replace(/\s{2,}/g,' ')), // 連続した半角スペースが含まれているとパースの不具合で描画が崩れるための処置
				},
				colors: this.chartData.borderColor.map((val)=>val.replace(/\s{2,}/g,' ')), // 連続した半角スペースが含まれているとパースの不具合で描画が崩れるための処置
			}
		},
		apexChartRadarSeries: function(){
			const series = [];
			const l = Math.min(this.chartData.valueName.length,this.chartData.valueList.length);
			for(let i=0;i<l;++i){
				series[i] = {};
				series[i].name = this.chartData.valueName[i];
				series[i].data = this.chartData.valueList[i];
			}
			return series
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
