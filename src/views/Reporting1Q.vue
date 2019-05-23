<template>
  <div class="question-layout" v-if="questionViewFlg">
    <page-title :raw-html="questionHtml">
      {{questionExplanation}}
    </page-title>
    <div style="padding-left:100px;">
      <bulb-text>あなたの回答を選択してください</bulb-text>
      <div v-for="question in questionList" :key="question.QUESTION_NO">
        <question-title>
          <template v-slot:left>Question<span style="font-size:2em">{{question.QUESTION_NO}}</span></template>
          {{question.QUESTION_STR}}
        </question-title>
        <reporting-radio-header />
        <reporting-radio v-for="(answer, index) in question.answerList" :name="'Q_'+question.QUESTION_NO+'_'+index" :label="answer" :max-value="5" v-model="question.selectedNoList[index]" />
      </div>

    </div>
    <h2>フリーコメント（120文字まで）</h2>
    <textarea-balloon name="freeComment" id="freeComment" value="" v-model="freeComment"></textarea-balloon>
    <button-area>
      <text-before-button>講師の指示があるまでは<br>「回答」を押さないでください</text-before-button>
      <base-button text="回答" @click="nextPage" />
    </button-area>
  </div>
</template>

<script>
export default {
	// データ定義
	data: function(){
		return {
			pageType: 'reporting_1',
			questionNo: 1,
			questionList: [],
			questionName: "",
			questionHtml: "",
			questionExplanation : "",
			questionMaxValue : 0,
			questionViewFlg: false, // データセット後に描画を行う
			freeComment: "",
		}
	},
	// 初回処理（createdではDOM操作をしない）
	created: function () {
		console.log('-- '+this.pageType+'_q');
		// セッション情報の取得等
		this.isLogin();
		this.startSession(this.callback_getSession);
	},
	// メソッド群
	methods: {
		// バリデーション
		validation: function () {
			for( var qno in this.questionList ) {
				for( var ano = 0; ano < this.questionList[qno].ANSWER_CNT; ano++ ) {
					if( this.questionList[qno].selectedNoList[ano] == 0 ) {
						var questionNo = parseInt(qno)+1;
						alert("Question"+questionNo+"に未回答があります。\nご回答のご確認をお願いします。");
						return false;
					}
				}
			}
			this.freeComment = "aaaaaaaa";
			console.log(this.freeComment);
			if( this.freeComment == "" ) {
				if( confirm('未入力のフリーコメントがありますが、回答を完了しますか？') ){
					return true;
				} else {
					return false;
				}
			}
				/*
				smoke.confirm("未入力のフリーコメントがありますが、回答を完了しますか？", function(e){
					if (e){
						save();
					}
				}, {
					ok: "はい",
					cancel: "いいえ",
					classname: "custom-class",
					reverseButtons: true
				});
				*/
/*
			if(window.confirm('本当にいいんですね？')){
				location.href = "example_confirm.html"; // example_confirm.html へジャンプ
			}
*/
			return true;
		},
		// 前ページへ
		prevPage: function(e){
			this.questionNo--;
			this.$parent.session.question_atr[this.pageType].currentQuestionNo--;
			this.jump({ name: this.pageType+'_a' });
		},
		// 回答
		nextPage: function(e){
			if( this.validation() ) {
				var form = [];
				for( var no in this.questionList ) {
					form.push(this.questionList[no]);
				}
				console.log("memberId : "+this.getMemberId());
				this.submit(this.getAPIPath()+'/'+this.pageType + '/' + this.getMemberId(),form,this.collback_postData);
			}
		},
		// -- サーバサイドからのコールバック
		// セッション読み込み後
		callback_getSession: function() {
			// セッションを読み込み終わって状態を取得したら問題データを読み込む
			this.questionNo = this.$parent.session.question_atr[this.pageType].currentQuestionNo;
			this.getJson(this.getAPIPath()+'/'+this.pageType + '_q/' + this.getMemberId(),this.collback_getData);
			this.questionName = this.$parent.session.question_atr[this.pageType].QUESTION_NAME;
			this.questionHtml = this.$parent.session.question_atr[this.pageType].QUESTION_HTML;
			this.questionExplanation = this.$parent.session.question_atr[this.pageType].QUESTION_EXPLANATION;
			this.questionMaxValue = this.$parent.session.question_atr[this.pageType].ANSWER_SELECT_CNT;
		},
		// 問題データ取得後
		collback_getData: function(response) {
			this.questionList = response.data;
			this.questionViewFlg = true;
		},
		// 回答データ送信後
		collback_postData: function(response) {
			this.result = response.data;
			if( this.result != null ) {
				this.jump({ name: this.pageType+'_a' });
			} else {
				alert("通信が正常に完了しませんでした。電波の良いところで再度お試し下さい。");
			}
		}
	}
}
</script>

<style lang="scss">
.button-area{
  $_padding: 10px;
  $_icon-size: 30px;
  display: flex;
  justify-content: flex-end;
  &+&{
    margin-top: 5px;
  }
  [type="button"],
  [type="submit"],
  button,
  .button{
    align-self: center;
    background-color: rgb(68,114,196);
    height: 50px;
    font-size: 20px;
    color: #fff;
    border: none;
    appearance: none;
    cursor: pointer;
    padding: 0 1em;
    flex: 0 0 200px;
    margin-right: 50px;
  }
  .button-primary{
    background-color: #EB4D4B;
  }
}

</style>

