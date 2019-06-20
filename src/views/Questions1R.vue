<template>
  <div>
    <template v-for="(question, index) in questionList">
      <page-title :before-text="index===0?questionName:''" :id="'question'+question.QUESTION_NO">
        <template v-slot:left><span style="font-size:1.4em">Q</span>uestion<span  style="font-size:2.0em">{{question.QUESTION_NO}}</span></template>
        {{question.QUESTION_STR}}
      </page-title>
      <bar-chart v-if="chartViewFlg" :width="824" :height="400" :chart-options="barChartOptions[index]" />
      <button-area>
        <base-button text="次へ" v-if="question.QUESTION_NO<questionCnt" v-scroll-to="'#question'+(parseInt(question.QUESTION_NO)+1)" />
      </button-area>
    </template>
  </div>
</template>

<script>
export default {
	// データ定義
	data: function(){
		return {
			pageType: 'questions_1',
			questionCnt: 1,
			questionList: [],
			questionName: "",
			chartViewFlg: false, // データセット後に描画を行う
		}
	},
	// 初回処理（createdではDOM操作をしない）
	created: function () {
		console.log('-- '+this.pageType+'_r');
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
			this.questionName = this.$store.state.session.question_atr[this.pageType].QUESTION_NAME;
			this.questionCnt  = this.$store.state.session.question_atr[this.pageType].QUESTION_CNT;
			this.getJson(this.getAPIPath()+'/'+this.pageType + '_r/' + this.getMemberId(),this.collback_getData);
		},
		// 問題データ取得後
		collback_getData: function(response) {
			this.questionList = response.data.questionList;
			for( var no in this.questionList ) {
				var question = this.questionList[no];
				// グラフ用のタイトル
				question.questionStr = 'Q' + this.questionList[no].QUESTION_NO + '. ' + this.questionList[no].QUESTION_STR;
			}

			this.chartViewFlg = true;
			this.$forceUpdate();
		},
		onClick: function(e){
			e.preventDefault()
		}
	},
	computed: {
		barChartOptions: function(){
			var clist = [];
			// 問題毎
			for( var qno=0; qno < this.questionList.length; qno++ ) {
				var chart = {
					stroke: {
						colors: [
							this.questionList[qno].chartList[0].aggregateList.site.borderColor,
							this.questionList[qno].chartList[0].aggregateList.total.borderColor,
						]
					},
					fill: {
						colors: [
							this.questionList[qno].chartList[0].aggregateList.site.backgroundColor,
							this.questionList[qno].chartList[0].aggregateList.total.backgroundColor,
						]
					},
					series: [
						{
							name: this.questionList[qno].chartList[0].aggregateList.site.valueName,
							data: this.questionList[qno].chartList[0].aggregateList.site.valueList,
						},{
							name: this.questionList[qno].chartList[0].aggregateList.total.valueName,
							data: this.questionList[qno].chartList[0].aggregateList.total.valueList,
						},
					],
					stack: [
						{
							name: 'stack1',
							series: [0]
						},
						{
							name: 'stack2',
							series: [1]
						}
					],
					title: {
						text: null
					},
					xaxis: {
						categories: this.questionList[qno].answerShortList,
					},
					dataLabels: {
						suffix: '%'
					}
				};
				clist.push(chart);
			}
			return clist;
		}
	},
}
</script>

<style lang="scss">

</style>
