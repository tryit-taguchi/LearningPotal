<template>
  <div>
    <page-title  :raw-html="questionHtml">
      {{questionExplanation}}
    </page-title>

    <div class="graph" v-if="chartViewFlg">
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
			pageType: 'reporting_2',
			questionNo: 1,
			questionList: [],
			questionName: "",
			questionHtml: "",
			questionExplanation : "",
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
		}
	},
	// 初回処理（createdではDOM操作をしない）
	created: function () {
		console.log('-- '+this.pageType+'_a');
		// セッション情報の取得等
		this.isLogin(); // ログインチェック・ログインしていたらセッション取得
		this.startSession(this.callback_getSession);
	},
	// メソッド群
	methods: {
		// -- サーバサイドからのコールバック
		// セッション読み込み後
		callback_getSession: function() {
			// セッションを読み込み終わって状態を取得したら問題データを読み込む
			this.questionNo = this.$parent.session.question_atr[this.pageType].currentQuestionNo;
			this.getJson(this.getAPIPath()+'/'+this.pageType + '_a/' + this.getMemberId(),this.collback_getData);
			this.questionName = this.$parent.session.question_atr[this.pageType].QUESTION_NAME;
			this.questionHtml = this.$parent.session.question_atr[this.pageType].QUESTION_HTML;
			this.questionExplanation = this.$parent.session.question_atr[this.pageType].QUESTION_EXPLANATION;
			this.questionMaxValue = this.$parent.session.question_atr[this.pageType].ANSWER_SELECT_CNT;
		},
		// 問題データ取得後
		collback_getData: function(response) {
			this.questionList = response.data.questionList;
			this.chartList    = response.data.chartList;
			// あなた
			this.chartData1.valueMax     = this.chartList.property.valueMax;
			this.chartData1.questionStr  = this.chartList.member.questionStr;
			this.chartData1.answerList   = this.chartList.member.answerList;
			this.chartData1.borderCount  = this.questionList[0].answerList.length;
			for( var ano in this.questionList[0].answerList ) {
				this.chartData1.valueName[ano] = this.chartList.member.aggregateList[ano].valueName;
				this.chartData1.valueList[ano] = this.chartList.member.aggregateList[ano].valueList;
				this.chartData1.backgroundColor[ano] = this.chartList.member.aggregateList[ano].backgroundColor;
				this.chartData1.borderColor[ano] = this.chartList.member.aggregateList[ano].borderColor;
			}
			// 会場全体
			this.chartData2.valueMax     = this.chartList.property.valueMax;
			this.chartData2.questionStr  = this.chartList.site.questionStr;
			this.chartData2.answerList   = this.chartList.site.answerList;
			this.chartData2.borderCount  = this.questionList[0].answerList.length;
			for( var ano in this.questionList[0].answerList ) {
				this.chartData2.valueName[ano] = this.chartList.member.aggregateList[ano].valueName;
				this.chartData2.valueList[ano] = this.chartList.member.aggregateList[ano].valueList;
				this.chartData2.backgroundColor[ano] = this.chartList.member.aggregateList[ano].backgroundColor;
				this.chartData2.borderColor[ano] = this.chartList.member.aggregateList[ano].borderColor;
			}

			this.chartViewFlg = true;
		},
  },
/*
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
*/
}
</script>

<style scoped lang="scss">
.graph{
  display: flex;
  justify-content: center;
}
</style>