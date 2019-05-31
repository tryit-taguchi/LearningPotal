<template>
	<div style="width:412px;color:#000;background-color:#fff;">
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
					colors: this.chartData.backgroundColor
				},
				stroke: {
					colors: this.chartData.borderColor
				},
				colors: this.chartData.borderColor,
			}
		},
		apexChartRadarSeries: function(){
			return [
				{
					name: this.chartData.valueName[0],
					data: this.chartData.valueList[0],
				},
				{
					name: this.chartData.valueName[1],
					data: this.chartData.valueList[1],
				}
			]
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
