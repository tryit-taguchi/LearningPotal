<template>
  <div>
    <page-title>
      {{questionName}} 回答完了
    </page-title>
    <p style="font-size:3.6rem;text-align:center;margin:20px auto;">ご回答ありがとうございました。</p>
  </div>
</template>

<script>
export default {
  data: function(){
    return {
      pageType: 'examinations_1',
      questionNo: 1,
      questionList: [],
      questionName: "",
      questionViewFlg: false, // データセット後に描画を行う
      freeComment: ""
    }
  },
  created: function () {
    console.log('-- '+this.pageType+'_r');
    // セッション情報の取得等
    this.isLogin();
    this.startSession(this.callback_getSession);
  },
	// メソッド群
	methods: {
    // -- サーバサイドからのコールバック
    // セッション読み込み後
    callback_getSession: function() {
      // セッションを読み込み終わって状態を取得したら問題データを読み込む
      this.questionName = this.$store.state.session.question_atr[this.pageType].QUESTION_NAME;
      this.questionViewFlg = true;
    },
  },
}
</script>

<style lang="scss">
  
</style>