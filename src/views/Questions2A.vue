<template>
	<div>
		<template v-for="question in questionList">
			<page-title :before-text="questionName">
				<template v-slot:left><span style="font-size:1.4em">Q</span>uestion<span  style="font-size:2.0em">{{question.QUESTION_NO}}</span></template>
				{{question.QUESTION_STR}}
			</page-title>
			<bar-chart v-if="chartViewFlg" :width="824" :height="400" :chart-options="barChartOptions" />
			<button-area>
				<base-button text="次の質問へ" @click="nextPage" />
			</button-area>
		</template>
	</div>
</template>

<script>
export default {
	// データ定義
	data: function(){
		return {
			pageType: 'questions_2',
			questionNo: 1,
			questionName: "",
			questionList: [],
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
		// 次の質問へ
		nextPage: function(e){
			if( this.$store.state.session.question_atr[this.pageType].currentQuestionNo < this.$store.state.session.question_atr[this.pageType].QUESTION_CNT ) {
				this.$store.commit('incrementCurrentQuestionNo', this.pageType)
				this.jump({ name: this.pageType+'_q' });
			} else {
				this.$store.commit('completedQuestion', this.pageType)
				this.jump({ name: this.pageType+'_r' });
			}
		},
		// -- サーバサイドからのコールバック
		// セッション読み込み後
		callback_getSession: function() {
			// セッションを読み込み終わって状態を取得したら問題データを読み込む
			this.questionNo = this.$store.state.session.question_atr[this.pageType].currentQuestionNo;
			this.questionName = this.$store.state.session.question_atr[this.pageType].QUESTION_NAME;
			this.getJson(this.getAPIPath()+'/'+this.pageType + '_a/' + this.getMemberId() + '/' + this.questionNo,this.collback_getData);
		},
		// 問題データ取得後
		collback_getData: function(response) {
			this.questionList = response.data.questionList;
			for( var no in this.questionList ) {
				var question = this.questionList[no];
				// グラフ用のタイトル
				question.questionStr = 'Q' + this.questionList[no].QUESTION_NO + '. ' + this.questionList[no].QUESTION_STR;
				//question.answerList = ["①100台","②1～5台","③6～9台","④10台以上"];
				//question.valueList = [75,75,0,0];
				//question.sumList = [3,3,0,0];
				//question.selectedNo = null;
			}

			this.chartViewFlg = true;
			this.$forceUpdate();
		},
	},
	computed: {
		barChartOptions: function(){
			return {
				stroke: {
					colors: [
						this.questionList[0].chartList[0].aggregateList.site.borderColor,
						this.questionList[0].chartList[0].aggregateList.member.borderColor,
					]
				},
				fill: {
					colors: [
						this.questionList[0].chartList[0].aggregateList.site.backgroundColor,
						this.questionList[0].chartList[0].aggregateList.member.backgroundColor,
					]
				},
				series: [
					{
						name: this.questionList[0].chartList[0].aggregateList.site.valueName,
						data: this.questionList[0].chartList[0].aggregateList.site.sumList.map((v,i)=>this.questionList[0].chartList[0].aggregateList.member.selectedNoList.includes(i)?0:v),
					},{
						name: this.questionList[0].chartList[0].aggregateList.member.valueName,
						data: this.questionList[0].chartList[0].aggregateList.site.sumList.map((v,i)=>this.questionList[0].chartList[0].aggregateList.member.selectedNoList.includes(i)?v:0),
					},
				],
				stack: [
					{
						name: 'stack1',
						series: [0,1]
					}
				],
				title: {
					text: this.questionList[0].questionStr
				},
				xaxis: {
					categories: this.questionList[0].answerList,
				},
				dataLabels: {
					suffix: '人'
				}
			}
		}
	},
}
</script>

<style lang="scss">

</style>
