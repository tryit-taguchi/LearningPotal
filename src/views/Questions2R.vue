<template>
  <div>
    <div v-for="question in questionList" :key="question.QUESTION_NO">
      <page-title :before-text="questionName">
        <template v-slot:left><span style="font-size:1.4em">Q</span>uestion<span  style="font-size:2.0em">{{question.QUESTION_NO}}</span></template>
        {{question.QUESTION_STR}}
      </page-title>
      <bar-chart-result v-if="chartViewFlg" :width="824" :height="400" :chart-data="question"></bar-chart-result>
      <button-area>
        <base-button text="次へ" @click="prevPage" v-if="question.QUESTION_NO<questionCnt" />
      </button-area>
    </div>
  </div>
</template>

<script>
export default {
	// データ定義
	data: function(){
		return {
			pageType: 'questions_2',
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
		// バリデーション
		validation: function () {
			return true;
		},
		// 前ページへ
		prevPage: function(e){
			this.jump({ name: this.pageType+'_a' });
		},
		// 回答
		nextPage: function(e){
		
		},
		// -- サーバサイドからのコールバック
		// セッション読み込み後
		callback_getSession: function() {
			// セッションを読み込み終わって状態を取得したら問題データを読み込む
			this.getJson(process.env.VUE_APP_API_URL_BASE+'/'+this.pageType + '_r/' + this.getMemberId(),this.collback_getData);
			this.questionName = this.$parent.session.question_atr[this.pageType].QUESTION_NAME;
			this.questionCnt  = this.$parent.session.question_atr[this.pageType].QUESTION_CNT;
		},
		// 問題データ取得後
		collback_getData: function(response) {
			this.questionList = response.data;
			for( var no in this.questionList ) {
				var question = this.questionList[no];
				// グラフ用のタイトル
				question.questionStr = 'Q' + this.questionList[no].QUESTION_NO + '. ' + this.questionList[no].QUESTION_STR;
			}

			this.chartViewFlg = true;
			this.$forceUpdate();
		},
	}
}
</script>

<style lang="scss">

</style>
