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
			},
		}
	},
	computed: {
		processedData: function(){
			var cdata = {
					labels: this.chartData.answerList,
					selectedId: this.chartData.selectedNo, // 「あなたの回答」判別のために加えた独自のプロパティ
					datasets: [],
				};
				for(var no=0; no<this.chartData.borderCount; no++) {
					var setdata = {
						label: this.chartData.valueName[no],
						data: this.chartData.valueList[no],
						backgroundColor: this.chartData.backgroundColor[no],
						fill:false,
						borderColor: this.chartData.borderColor[no],
						borderWidth: 2,
					};
					cdata.datasets.push(setdata);
				}
			return cdata;
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
