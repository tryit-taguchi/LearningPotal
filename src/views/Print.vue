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
/*
			clist :
			[
				{
					pageType: 'reporting_1',
					questionNo: 1,
					questionList: [],
					questionName: "",
					questionHtml: "",
					questionExplanation : "",
					chartEnable: false,
					chartList: {},
					chartData1: { // グラフ１
						valueMax: 5,
						questionStr: "",
						answerList: [],
						valueName: [],
						valueList: [],
						backgroundColor: [],
						borderColor: [],
						borderCount: 0,
					},
					chartData2: { // グラフ２
						valueMax: 5,
						questionStr: "",
						answerList: [],
						valueName: [],
						valueList: [],
						backgroundColor: [],
						borderColor: [],
						borderCount: 0,
					},
					chartViewFlg: false, // データセット後に描画を行う
				},
				{
					pageType: 'reporting_2',
					questionNo: 1,
					questionList: [],
					questionName: "",
					questionHtml: "",
					questionExplanation : "",
					chartEnable: false,
					chartList: {},
					chartData1: { // グラフ１
						valueMax: 5,
						questionStr: "",
						answerList: [],
						valueName: [],
						valueList: [],
						backgroundColor: [],
						borderColor: [],
						borderCount: 0,
					},
					chartData2: { // グラフ２
						valueMax: 5,
						questionStr: "",
						answerList: [],
						valueName: [],
						valueList: [],
						backgroundColor: [],
						borderColor: [],
						borderCount: 0,
					},
					chartViewFlg: false, // データセット後に描画を行う
				},
				{
					pageType: 'reporting_3',
					questionNo: 1,
					questionList: [],
					questionName: "",
					questionHtml: "",
					questionExplanation : "",
					chartEnable: false,
					chartList: {},
					chartData1: { // グラフ１
						valueMax: 5,
						questionStr: "",
						answerList: [],
						valueName: [],
						valueList: [],
						backgroundColor: [],
						borderColor: [],
						borderCount: 0,
					},
					chartData2: { // グラフ２
						valueMax: 5,
						questionStr: "",
						answerList: [],
						valueName: [],
						valueList: [],
						backgroundColor: [],
						borderColor: [],
						borderCount: 0,
					},
					chartViewFlg: false, // データセット後に描画を行う
				},
			],
*/
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

//			console.log(response.data.answers.raderChartList);

			this.chartViewFlg = true;
		}.bind(this));
	},
	// メソッド群
	methods: {
/*
		// -- サーバサイドからのコールバック
		// セッション読み込み後
		callback_getSession: function() {
			// セッションを読み込み終わって状態を取得したら回答データを読み込む
			/print/20190619/追浜グランドライブ/1

			for( var no in this.clist ) {
				var sub = this.clist[no];
				var pageType = sub.pageType;

				this.getJson(this.getAPIPath()+'/'+pageType + '_a/' + this.getMemberId(),function(response) {
					// 問題データ取得後
					this.questionList = response.data.questionList;
					this.chartList    = response.data.chartList;
					// あなた
					this.chartData1.valueMax     = this.chartList.property.valueMax;
					this.chartData1.questionStr  = this.chartList.member.questionStr;
					this.chartData1.answerList   = this.chartList.member.answerList;
					this.chartData1.borderCount  = this.questionList[0].answerList.length;

					if( this.chartList.member.aggregateList[0].valueList != null ) {
						this.chartEnable = true;
					}
					for( var ano in this.questionList[0].answerList ) {
						this.chartData1.valueName[ano] = this.chartList.member.aggregateList[ano].valueName;
						if( this.chartList.member.aggregateList[ano].valueList != null ) {
							this.chartData1.valueList[ano] = this.chartList.member.aggregateList[ano].valueList;
						} else {
							this.chartData1.valueList[ano] = [0,0,0,0,0,0,0,0,0,0,];
						}
						this.chartData1.backgroundColor[ano] = this.chartList.member.aggregateList[ano].backgroundColor;
						this.chartData1.borderColor[ano] = this.chartList.member.aggregateList[ano].borderColor;
					}
					// 会場全体
					this.chartData2.valueMax     = this.chartList.property.valueMax;
					this.chartData2.questionStr  = this.chartList.site.questionStr;
					this.chartData2.answerList   = this.chartList.site.answerList;
					this.chartData2.borderCount  = this.questionList[0].answerList.length;
					for( var ano in this.questionList[0].answerList ) {
						this.chartData2.valueName[ano] = this.chartList.site.aggregateList[ano].valueName;
						this.chartData2.valueList[ano] = this.chartList.site.aggregateList[ano].valueList;
						this.chartData2.backgroundColor[ano] = this.chartList.site.aggregateList[ano].backgroundColor;
						this.chartData2.borderColor[ano] = this.chartList.site.aggregateList[ano].borderColor;
					}
					this.chartViewFlg = true;
				}.bind(sub));
				sub.questionName = this.$parent.session.question_atr[pageType].QUESTION_NAME;
				sub.questionHtml = this.$parent.session.question_atr[pageType].QUESTION_HTML;
				sub.questionExplanation = this.$parent.session.question_atr[pageType].QUESTION_EXPLANATION;
				sub.questionMaxValue = this.$parent.session.question_atr[pageType].ANSWER_SELECT_CNT;
			}
		},
*/
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
