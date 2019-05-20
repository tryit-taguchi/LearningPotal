<template>
  <div>
    <div v-for="question in questionList" key="question1">
      <page-title :before-text="questionName">
        <template v-slot:left><span style="font-size:1.4em">Q</span>uestion<span  style="font-size:2.0em">{{question.QUESTION_NO}}</span></template>
        {{question.QUESTION_STR}}
      </page-title>
      <bar-chart-answer v-if="chartViewFlg" :width="824" :height="400" :chart-data="question"></bar-chart-answer>
      <div style="text-align:right;">
        <!--<base-button text="前へ" @click="prevPage" />-->
        <base-button text="次の質問へ" @click="nextPage" />
      </div>
    </div>
  </div>
</template>

<script>
export default {
	// データ定義
	data: function(){
		return {
			pageType: 'questions_2',
			questionNo: 1,
			questionList: [],
			questionName: "",
			chartViewFlg: false, // データセット後に描画を行う
		}
	},
	// 初回処理（createdではDOM操作をしない）
	created: function () {
		console.log('-- '+this.pageType+'_a');
		// セッション情報の取得等
		this.isLogin(); // ログインチェック・ログインしていたらセッション取得
		this.startSession(this.callback_getSession);
//questionList
	},
	// メソッド群
	methods: {
		// バリデーション
		validation: function () {
			return true;
		},
		// 前ページへ
		prevPage: function(e){
			this.questionNo--;
			this.jump({ name: this.pageType+'_q' });
		},
		// 次の質問へ
		nextPage: function(e){
			if( this.$parent.session.question_atr[this.pageType].currentQuestionNo < this.$parent.session.question_atr[this.pageType].QUESTION_CNT ) {
				this.$parent.session.question_atr[this.pageType].currentQuestionNo++;
				this.jump({ name: this.pageType+'_q' });
			} else {
				this.jump({ name: this.pageType+'_r' });
			}
		},
		// -- サーバサイドからのコールバック
		// セッション読み込み後
		callback_getSession: function() {
			// セッションを読み込み終わって状態を取得したら問題データを読み込む
			this.questionNo = this.$parent.session.question_atr[this.pageType].currentQuestionNo;
			this.getJson(process.env.VUE_APP_API_URL_BASE+'/'+this.pageType + '_a/' + this.getMemberId() + '/' + this.questionNo,this.collback_getData);
			this.questionName = this.$parent.session.question_atr[this.pageType].QUESTION_NAME;
		},
		// 問題データ取得後
		collback_getData: function(response) {
			this.questionList = response.data;
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
	}
}
</script>

<style lang="scss">

</style>
