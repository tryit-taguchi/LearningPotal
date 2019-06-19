<template>
	<!-- データの読み込みが終わったらレンダリング -->
	<div v-if="chartViewFlg">
		<!-- 該当受講者分ループを回す 1受講者につき2枚 -->
		<div v-for="(sub,no) in this.answers">
			<section class="print_pages">
				<div>
					試乗付き勉強会レポート
				</div>
				<!-- 受講者名 -->
				<div>
				{{sub.member.COMPANY_NAME}} {{sub.member.MEMBER_NAME}}
				</div>
				<!-- レーダーチャート -->
				<div v-for="(raderChart, questionType, index) in sub.raderChartList">
					{{raderChart.questionName}}
					<div class="graph">
						<radar-chart-reporting :width="412" :height="450" :chart-data="raderChart.member" />
						<radar-chart-reporting :width="412" :height="450" :chart-data="raderChart.site" />
					</div>
				</div>
				<!-- 比較グラフ（まとめ①） -->
				{{sub.compareBarChartName}}
				<div v-for="(compareChart, questionNo, index) in sub.compareBarChartList">
					<div>{{compareChart.title}}</div>
					<div class="graph">
						<bar-chart :width="412" :height="400" :chart-options="compareChart.before" />
						<bar-chart :width="412" :height="400" :chart-options="compareChart.after" />
					</div>
				</div>
				<!-- 複数選択グラフ（まとめ②） -->
				{{sub.multipleBarChartName}}
				<div v-for="(multipleChart, questionNo, index) in sub.multipleBarChartList">
					<div>{{multipleChart.title}}</div>
					<div class="graph">
						<bar-chart :width="824" :height="450" :chart-options="multipleChart.chart" />
					</div>
				</div>
				<div>
					キャッチフレーズ<!-- 1枚目のBest3 -->
				</div>
				<div v-for="(phrase, index) in catchphrase.catchphraseRankList">
					<div>
						Best {{phrase.RANK}} {{phrase.CATCHPHRASE_STR}} 
					</div>
				</div>
				<hr><!-- とりあえずページ分けをわかりやすくするためにhr入れてるほんとはいらない -->
				<div>
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
				<hr><!-- とりあえずページ分けをわかりやすくするためにhr入れてるほんとはいらない -->
			</section>
		</div>

	</div>
</template>

<script>
export default {
	// データ定義
	data: function(){
		return {
			chartViewFlg : false,
			catchphrase : {},
			answers : [],
		}
	},
	// 初回処理（createdではDOM操作をしない）
	created: function () {
		console.log('-- '+this.pageType+'_a');
		console.log(this.$route.params);
		var lectureDate = this.$route.params.lectureDate;
		var siteName    = this.$route.params.siteName;
		var lectureType = this.$route.params.lectureType;
		this.getJson(this.getAPIPath()+'/print/' + lectureDate + '/' + siteName + '/' + lectureType,function(response) {
			this.catchphrase = response.data.catchphrase; // キャッチフレーズデータ
			this.answers = response.data.answers; // グラフ用データ（該当受講者分のリスト）
			this.chartViewFlg = true;
		}.bind(this));
	},
	// メソッド群
	methods: {
	},
}
</script>

<style scoped lang="scss">
.graph{
	width: 834px;
	display: flex;
  justify-content: space-between;
	margin: 10px auto 0;
	background-color: #fff;
}

/*A3横*/
.print_pages{
	width: 410mm;
/*	height: 292mm;*/
	page-break-after: always;
}
/*最後のページは改ページを入れない*/
.print_pages:last-child{
	page-break-after: auto;
}


</style>
