<template>
  <div v-if="questionViewFlg">
    <page-title>
      {{questionName}}（選択）
    </page-title>
    <div style="padding-left:100px;">
      <bulb-text>あなたの回答を選択してください</bulb-text>
      <template v-for="question in questionList">
        <question-title>
          <template v-slot:left>Question<span style="font-size:2em">{{question.QUESTION_NO}}</span></template>
          {{question.QUESTION_STR}}
        </question-title>
        <radio-block-list style="padding-left:150px;" :labels="question.answerList" :name="'Q_'+question.QUESTION_NO" v-model="question.selectedNo" :key="'radio'+question.QUESTION_NO" />
      </template>
    </div>
    <h2>フリーコメント（120文字まで）</h2>
    <textarea-balloon name="freeComment" id="freeComment" value="" v-model="freeComment"></textarea-balloon>
    <button-area>
      <text-before-button>講師の指示があるまでは<br>「回答」を押さないでください</text-before-button>
      <base-button text="回答" @click="nextPage"/>
    </button-area>
    <v-dialog/>
  </div>
</template>

<script>
export default {
  data(){
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
    console.log('-- '+this.pageType+'_q');
    // セッション情報の取得等
    this.isLogin();
    this.startSession(this.callback_getSession);
  },
  // メソッド群
  methods: {
    // バリデーション
    validation: function (callback) {
      for( var no in this.questionList ) {
        if( this.questionList[no].selectedNo == null ) {
          // alert("回答を選択して下さい。");
          this.$modal.show('dialog', {
            text: '回答を選択して下さい。',
            buttons: [
              {title: 'OK'}
            ]
          })
          return false;
        }
      }
			if( this.freeComment == "" ) {
				this.$modal.show('dialog', {
					text: '未入力のフリーコメントがありますが、回答を完了しますか？',
					buttons: [
						{
						  title: 'OK',
						  handler: () => {callback()} // ボタンが押されたときに実行される
						},
						{
						  title: 'キャンセル',
						  default: true // Enterキーを押したときに押されるボタンを指定する
						}
					]
				});
			} else {
				callback();
			}
			return true;
    },
    // 回答
    nextPage: function(e){
      this.validation(this.callback_formSubmit);
    },
    // フォームのSubmit
    callback_formSubmit: function(e){
      var answerList = [];
      for( var no in this.questionList ) {
        answerList.push(this.questionList[no]);
      }
      var form = {};
      form.answerList = answerList;
      console.log("memberId : "+this.getMemberId());
      this.submit(this.getAPIPath()+'/'+this.pageType + '/' + this.getMemberId(),form,this.collback_postData);
    },
    // -- サーバサイドからのコールバック
    // セッション読み込み後
    callback_getSession: function() {
      // セッションを読み込み終わって状態を取得したら問題データを読み込む
      this.getJson(this.getAPIPath()+'/'+this.pageType + '_q/' + this.getMemberId(),this.collback_getData);
      this.questionName = this.$parent.session.question_atr[this.pageType].QUESTION_NAME;
    },
    // 問題データ取得後
    collback_getData: function(response) {
      this.questionList = response.data.questionList;
      this.questionViewFlg = true;
    },
    // 回答データ送信後
    collback_postData: function(response) {
      this.result = response.data;
      if( this.result != null ) {
        this.jump({ name: this.pageType+'_c' });
      } else {
        // alert("通信が正常に完了しませんでした。電波の良いところで再度お試し下さい。");
        this.$modal.show('dialog', {
          text: '通信が正常に完了しませんでした。電波の良いところで再度お試し下さい。',
          buttons: [
            {title: 'OK'}
          ]
        });
      }
    }
  }
}
</script>

<style lang="scss">

</style>
