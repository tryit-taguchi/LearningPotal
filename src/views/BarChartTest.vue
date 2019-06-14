<template>
	<div>
		<bar-chart-answer-new :width="824" :height="400" :chart-options="barChartOptions" />
		<input type="text" v-model="testTitle">
		<table>
			<tr>
				<td></td>
				<td v-for="(category,categoryIndex) in testXaxisCategories"><input type="text" v-model="testXaxisCategories[categoryIndex]" style="width:100px"></td>
			</tr>

			<tr v-for="(series,seriesIndex) in testSeries">
				<td>
					<input type="text" v-model="testSeries[seriesIndex].name" style="width:100px">
				</td>
				<td v-for="(data,dataIndex) in series.data">
					<input type="number" v-model.number="testSeries[seriesIndex].data[dataIndex]" style="width:100px" min="0">
				</td>
			</tr>
		</table>
		<input type="radio" name="stackType" id="stackTypeA" value="a" v-model="testStackType">
		<label for="stackTypeA">StackType A</label>
		<input type="radio" name="stackType" id="stackTypeB" value="b" v-model="testStackType">
		<label for="stackTypeB">StackType B</label>
		<input type="radio" name="stackType" id="stackTypeC" value="c" v-model="testStackType">
		<label for="stackTypeC">StackType C</label>
	</div>
</template>

<script>
export default {
	// データ定義
	data: function(){
		return {
			testTitle: "てすと",
			testSeries: [
				{
					name: 'てすと１',
					data: [1,2,3,4],
				},{
					name: 'てすと2',
					data: [6,7,8,9],
				},{
					name: 'てすと3',
					data: [9,10,11,12],
				},
			],
			testXaxisCategories: ['選択肢１','選択肢２','選択肢３','選択肢４'],
			testStackType: 'b'
		}
	},
	computed: {
		testStack: function(){
			if(this.testStackType==='a'){
				return [
					{name: 'stack1',series: [0]},
					{name: 'stack2',series: [1]},
					{name: 'stack3',series: [2]}
				]
			}else if(this.testStackType==='b'){
				return [
					{name: 'stack1',series: [0,1]},
					{name: 'stack2',series: [2]}
				]
			}else if(this.testStackType==='c'){

				return [
					{name: 'stack1',series: [0,1,2]}
				]
			}
		},
		barChartOptions: function(){
			return {
				stroke: {
					colors: [
						'rgb(255, 206, 86)',
						'rgb(255, 99, 132)',
						'rgb(86, 206, 255)',
					]
				},
				fill: {
					colors: [
						'rgba(255, 206, 86, 0.2)',
						'rgba(255, 99, 132, 0.2)',
						'rgba(86, 206, 255, 0.2)',
					]
				},
				series: this.testSeries,
				stack: this.testStack,
				title: {
					text: this.testTitle
				},
				xaxis: {
					categories: this.testXaxisCategories,
				}
			}
		}
	},
}
</script>

<style lang="scss">

</style>
