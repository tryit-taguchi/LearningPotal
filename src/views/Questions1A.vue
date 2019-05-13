<template>
  <div>
    <page-title before-text="オリエンテーション 回答（棒グラフ）">
      <template v-slot:left><span style="font-size:1.4em">Q</span>uestion<span  style="font-size:2.0em">{{question.QUESTION_NO}}</span></template>
      {{question.QUESTION_STR}}
    </page-title>
    <p style="font-size: 3rem;margin:0;text-align:center;">ここに結果グラフ</p>
    <div style="text-align:right;">
      <base-button text="前へ" @click="prevPage" v-if="questionNo>1" />
      <base-button text="回答" @click="nextPage" />
    </div>
  </div>
</template>

<script>
export default {
	// データ定義
	data: function(){
		return {
			session: {},
			pageType: 'questions_1',
			questionNo: 1,
			question: {},
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
		// バリデーション
		validation: function () {
			return true;
		},
		// 前ページへ
		prevPage: function(e){
			this.questionNo--;
			this.jump({ name: this.pageType+'_q' });
		},
		// 回答
		nextPage: function(e){
			this.questionNo++;
			this.session.question_atr[this.pageType].currentQuestionNo++;
			this.jump({ name: this.pageType+'_q' });
		},
		// -- サーバサイドからのコールバック
		// セッション読み込み後
		callback_getSession: function() {
			console.log("セッションを読み込み終わって状態を取得したら問題データを読み込む");
			this.$parent.questionNo = this.$parent.session.question_atr[this.pageType].currentQuestionNo;
			this.getJson(process.env.VUE_APP_API_URL_BASE+'/'+this.pageType+'/' + this.questionNo+'/' + this.getMemberId(),this.collback_getData);
			//this.questionName = this.$parent.session.question_atr[this.pageType].QUESTION_NAME;
		},
		// 問題データ取得
		collback_getData: function(response) {
			console.log("getData");
			this.question = response.data;
		}
	}
}
</script>

<style lang="scss">
.radio-block-list {
  font-size: 3rem;
}
.radio-block-list input[type="radio"] {
  display: none;
}
.radio-block-list label {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  cursor: pointer;
  vertical-align: middle;
  margin-bottom: 5px;
}
.radio-block-list label span:nth-child(1) {
  -webkit-box-flex: 0;
      -ms-flex: 0 1 calc(1024px - 720px);
          flex: 0 1 calc(1024px - 720px);
  background-color: #666;
  margin-left: auto;
  text-align: center;
  color: #fff;
  padding: 5px;
}
.radio-block-list label span:nth-child(2) {
  -webkit-box-flex: 0;
      -ms-flex: 0 0 720px;
          flex: 0 0 720px;
  background-color: #EFEFEF;
  color: #333;
  padding: 5px;
}
.radio-block-list input[type="radio"]:checked + label {
  color: #EB4D4B;
}
.radio-block-list input[type="radio"]:checked + label span:nth-child(1) {
  background-color: #f07a79;
}
.radio-block-list input[type="radio"]:checked + label span:nth-child(2) {
  background-color: #EB4D4B;
  color: #fff;
}
</style>
