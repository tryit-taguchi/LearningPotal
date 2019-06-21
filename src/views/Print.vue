<template>
	<!-- データの読み込みが終わったらレンダリング -->
	<div class="print-inner" v-if="printViewFlg">
		<!-- 該当受講者分ループを回す 1受講者につき2枚 -->
		<div v-for="(sub,no) in this.answers.slice(0, 1)">
			<div class="print_pages">
				<div class="page-1">
					<div class="header">
						試乗付き勉強会レポート
						<!-- 受講者名 -->
						<div>{{sub.member.COMPANY_NAME}} {{sub.member.MEMBER_NAME}}</div>
						<!-- 席番号 -->
						<div>{{sub.member.SEAT_CD}}</div>
					</div>
					<!-- レーダーチャート -->
					<template v-for="(raderChart, questionType, index) in sub.raderChartList">
						<div :class="'radarh'+index">{{raderChart.questionName}}</div>
						<div :class="'radar'+index+' graph'">
							<radar-chart-reporting :width="375" :height="400" :chart-data="raderChart.member" />
							<radar-chart-reporting :width="375" :height="400" :chart-data="raderChart.site" />
						</div>
					</template>
					<!-- 比較グラフ（まとめ①） -->
					<div class="bar1h">{{sub.compareBarChartName}}</div>
					<template v-for="(compareChart, questionNo, index) in sub.compareBarChartList">
						<div :class="'bar1h'+index">{{compareChart.title}}</div>
						<div :class="'bar1'+index+' graph'">
							<bar-chart :width="375" :height="450" :chart-options="compareChart.before" />
							<bar-chart :width="375" :height="450" :chart-options="compareChart.after" />
						</div>
					</template>
					<!-- 複数選択グラフ（まとめ②） -->
					<div class="bar2h">{{sub.multipleBarChartName}}</div>
					<template v-for="(multipleChart, questionNo, index) in sub.multipleBarChartList" :class="'bar2-'+index">
						<div :class="'bar2h'+index">{{multipleChart.title}}</div>
						<div :class="'bar2'+index+' graph'">
							<bar-chart :width="750" :height="450" :chart-options="multipleChart.chart" />
						</div>
					</template>
					<div class="catchphrase">
						キャッチフレーズ<!-- 1枚目のBest3 -->
						<div v-for="(phrase, index) in catchphrase.catchphraseRankList">
							<div>
								Best {{phrase.RANK}} {{phrase.CATCHPHRASE_STR}} 
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="print_pages">
				<div class="page-2">
					<div class="page-2-header">
						過去のキャッチフレーズ<!-- 2枚目の前半後半のBest1 3列x9行の27個分 -->
					</div>
					<div v-for="(phrase, index) in catchphrase.catchphraseOldList">
						<div>
							{{phrase.catchphraseDate}}<br>
							{{phrase.siteName}}<br>
							前半 {{phrase.catchphraseStr[1]}}<br>
							後半 {{phrase.catchphraseStr[2]}}<br>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<p v-else>
		読み込み中
	</p>
</template>

<script>
export default {
	// データ定義
	data: function(){
		return {
			printViewFlg : false,
			catchphrase : {},
			answers : [],
		}
	},
	// 初回処理（createdではDOM操作をしない）
	created: function () {
		console.log(document.body.setAttribute('data-theme','print'))
		console.log('-- '+this.pageType+'_a');
		console.log(this.$route.params);
		var lectureDate = this.$route.params.lectureDate;
		var siteName    = this.$route.params.siteName;
		var lectureType = this.$route.params.lectureType;
		this.getJson(this.getAPIPath()+'/print/' + lectureDate + '/' + siteName + '/' + lectureType,function(response) {
			this.catchphrase = response.data.catchphrase; // キャッチフレーズデータ
			const answers = response.data.answers; // グラフ用データ（該当受講者分のリスト）
			answers.map(sub=>{
				Object.keys(sub.raderChartList).map(type=>{
					sub.raderChartList[type].member.animations = false
					sub.raderChartList[type].site.animations = false
				})
				Object.keys(sub.compareBarChartList).map(type=>{
					sub.compareBarChartList[type].before.animations = false
					sub.compareBarChartList[type].after.animations = false
				})
				Object.keys(sub.multipleBarChartList).map(type=>{
					sub.multipleBarChartList[type].chart.animations = false
				})
			})
			this.answers = answers; // グラフ用データ（該当受講者分のリスト）
			this.printViewFlg = true;
		}.bind(this));
	},
	// メソッド群
	methods: {
	},
}
</script>

<style scoped lang="scss">
@page { size: A3 landscape }
.print-inner{
	width: 100%;
	height: 100%;
	background-color: #fff;
	color: #000;
}

.graph{
	width: 750px;
	display: flex;
	justify-content: space-between;
	margin: 0 auto;
	background-color: #fff;
	border: 1px solid #000;
}

/*A3横*/
.print_pages{
	$w: 2250px;
	$h: $w/1.45;
	width: $w;
	height: $h;
	page-break-after: always;
	overflow: hidden;
	border: 3px double #000;
}
/*最後のページは改ページを入れない*/
.print_pages:last-child{
	page-break-after: auto;
}
.page-1{
	width: 100%;
	height: 100%;
	background-color: #ccc;
	overflow: hidden;
	display: grid;
	grid-template-columns: 1fr 1fr 1fr;
	grid-template-rows: 100px 20px 400px 20px 20px 450px 20px 20px 450px;
	grid-template-areas:
		"header header header"
		"radarh0 radarh1 radarh2"
		"radar0 radar1 radar2"
		"bar1h bar1h bar1h"
		"bar1h0 bar1h1 bar1h2"
		"bar10 bar11 bar12"
		"bar2h catchphrase catchphrase"
		"bar2h1 catchphrase catchphrase"
		"bar20 catchphrase catchphrase";
}
.header { grid-area: header; }
.radarh0 { grid-area: radarh0; }
.radarh1 { grid-area: radarh1; }
.radarh2 { grid-area: radarh2; }
.radar0 { grid-area: radar0; }
.radar1 { grid-area: radar1; }
.radar2 { grid-area: radar2; }
.bar1h { grid-area: bar1h; }
.bar1h0 { grid-area: bar1h0; }
.bar1h1 { grid-area: bar1h1; }
.bar1h2 { grid-area: bar1h2; }
.bar10 { grid-area: bar10; }
.bar11 { grid-area: bar11; }
.bar12 { grid-area: bar12; }
.bar2h { grid-area: bar2h; }
.bar2h1 { grid-area: bar2h1; }
.bar21 { grid-area: bar20; }
.catchphrase { grid-area: catchphrase; }

.page-2{
	width: 100%;
	height: 100%;
	background-color: #999;
	overflow: hidden;
	display: grid;
	grid-template-columns: 1fr 1fr 1fr;
	grid-template-rows: 100px 1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr;
}
.page-2-header{
	grid-column: 1/4;
	grid-row: 1/2;
}
</style>
