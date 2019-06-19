<template>
	<div>
    <page-title>
      <template v-slot:left>比較</template>
      000
    </page-title>
		<div v-for="(sub,no) in clist">
			<div>
				<div style="font-size:150%" :id="'chart'+no">　{{sub.questionName}}</div>
				<div class="graph" v-if="sub.chartViewFlg">
					<radar-chart-reporting :width="412" :height="450" :chart-data="sub.chartData1" />
					<radar-chart-reporting :width="412" :height="450" :chart-data="sub.chartData2" />
				</div>
	      <button-area>
	        <base-button text="次へ" v-if="no<clist.length-1" v-scroll-to="'#chart'+(parseInt(no)+1)" />
	      </button-area>
	    </div>
		</div>
	</div>
</template>

<script>
export default {
	// データ定義
	data: function(){
		return {
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
		}
	},
	// 初回処理（createdではDOM操作をしない）
	created: function () {
		console.log('-- '+this.pageType+'_a');
		console.log(this.$route.params);

		// セッション情報の取得等
		this.isLogin(); // ログインチェック・ログインしていたらセッション取得
		this.startSession(this.callback_getSession);
	},
	// メソッド群
	methods: {
		// -- サーバサイドからのコールバック
		// セッション読み込み後
		callback_getSession: function() {
			// セッションを読み込み終わって状態を取得したら回答データを読み込む
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
</style>
