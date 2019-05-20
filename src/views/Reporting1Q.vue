<template>
  <div class="question-layout" v-if="questionViewFlg">
    <page-title>
      <template v-slot:left><span v-html="questionHtml"></span></template>
      {{questionExplanation}}
    </page-title>
    <div style="padding-left:100px;">
      <p class="bulb">あなたの回答を選択してください</p>
      <div v-for="question in questionList" :key="'reporting_1'+question.QUESTION_NO">
        <question-title>
          <template v-slot:left>Question<span style="font-size:2em">{{question.QUESTION_NO}}</span></template>
          {{question.QUESTION_STR}}
        </question-title>
        <reporting-radio-header />
        <reporting-radio :name="'Q_'+question.QUESTION_NO+'_0'" label="現行" />
        <reporting-radio :name="'Q_'+question.QUESTION_NO+'_1'" label="新型" />
      </div>

    </div>
    <h2>フリーコメント（120文字まで）</h2>
    <textarea-balloon name="freeComment" id="freeComment" value=""></textarea-balloon>
    <div class="button-area">
      <p class="button-area-balloon">講師の指示があるまでは<br>「回答」を押さないでください</p>
      <input type="button" id="btnAnswer" value="回答">
    </div>
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
			questionViewFlg: false, // データセット後に描画を行う
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
			for( var no in this.questionList ) {
				if( this.questionList[no].selectedNo == null ) {
					alert("回答を選択して下さい。");
					return false;
				}
			}
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
				this.submit(process.env.VUE_APP_API_URL_BASE+'/'+this.pageType + '/' + this.getMemberId(),form,this.collback_postData);
			}
		},
		// -- サーバサイドからのコールバック
		// セッション読み込み後
		callback_getSession: function() {
			// セッションを読み込み終わって状態を取得したら問題データを読み込む
			this.questionNo = this.$parent.session.question_atr[this.pageType].currentQuestionNo;
			this.getJson(process.env.VUE_APP_API_URL_BASE+'/'+this.pageType + '_q/' + this.getMemberId(),this.collback_getData);
			this.questionName = this.$parent.session.question_atr[this.pageType].QUESTION_NAME;
			this.questionHtml = this.$parent.session.question_atr[this.pageType].QUESTION_HTML;
			this.questionExplanation = this.$parent.session.question_atr[this.pageType].QUESTION_EXPLANATION;
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
.bulb{
  background-image: url(../assets/icon_bulb.png);
  background-size: 50px 50px;
  background-repeat: no-repeat;
  background-position: left center;
  margin: 5px 0;
  padding-left: 50px + 10px;
  min-height: 50px;
  display: flex;
  // justify-content: center;
  align-items: center;
}

.button-area{
  $_padding: 10px;
  $_icon-size: 30px;
  display: flex;
  justify-content: flex-end;
  &+&{
    margin-top: 5px;
  }
  &-balloon{
    position: relative;
    align-self: center;
    background-color: rgba(36, 1, 8, 1);
    border: 2px solid #fff;
    border-radius: 4px;
    margin: 0 30px 0 0;
    background-image: url(../assets/icon_exclamation.png);
    background-size: $_icon-size $_icon-size;
    background-repeat: no-repeat;
    background-position: 10px center;
    line-height: 1;
    padding: $_padding $_padding $_padding $_padding*2+$_icon-size;
    &::before,
    &::after{
      display: block;
      content: "";
      width: 0;
      height: 0;
      border-style: solid;
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
    }
    &::before{
      border-width: 10px 0 10px 20px;
      border-color: transparent transparent transparent #fff;
      right: -20px;
    }
    &::after{
      border-width: 8px 0 8px 17px;
      border-color: transparent transparent transparent rgba(36, 1, 8, 1);
      right: -16px;
    }
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

