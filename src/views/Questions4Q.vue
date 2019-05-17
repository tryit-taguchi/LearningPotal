<template>
  <div v-if="questionViewFlg">
    <div v-for="question in questionList" key="question1">
      <page-title :before-text="questionName">
        <template v-slot:left><span style="font-size:1.4em">Q</span>uestion<span style="font-size:2.0em">{{question.QUESTION_NO}}</span></template>
        {{question.QUESTION_STR}}
      </page-title>
      <div style="padding-left:200px;">
        <p class="bulb">次の中から{{answerSelectCnt}}つ選んでください。</p>
        <checkbox-block-list :labels="question.answerList" :name="'cQ_'+questionNo" v-model="question.selectedNoList" :key="'checkbox'+questionNo" />
      </div>
      <div style="text-align:right;">
        <base-button text="前へ" @click="prevPage" v-if="questionNo>1" />
        <base-button text="回答" @click="nextPage" />
      </div>
    </div>
  </div>
</template>

<script>
export default {
	// データ定義
	data: function () {
		return {
			pageType: 'questions_4',
			current: 0,
			questionNo: 1,
			questionList: [{}],
			questionName: "",
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
				if( this.questionList[no].selectedNoList.length != this.answerSelectCnt ) {
					alert("回答を"+this.answerSelectCnt+"つ選択して下さい。");
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
				this.submit(process.env.VUE_APP_API_URL_BASE+'/'+this.pageType + '/' + this.getMemberId() + '/' + this.questionNo,form,this.collback_postData);
			}
		},
		// -- サーバサイドからのコールバック
		// セッション読み込み後
		callback_getSession: function() {
			// セッションを読み込み終わって状態を取得したら問題データを読み込む
			this.questionNo = this.$parent.session.question_atr[this.pageType].currentQuestionNo;
			this.getJson(process.env.VUE_APP_API_URL_BASE+'/'+this.pageType + '_q/' + this.getMemberId() + '/' + this.questionNo,this.collback_getData);
			this.questionName = this.$parent.session.question_atr[this.pageType].QUESTION_NAME;
			this.answerSelectCnt = this.$parent.session.question_atr[this.pageType].ANSWER_SELECT_CNT;
		},
		// 問題データ取得後
		collback_getData: function(response) {
			this.questionList = response.data;
			this.questionViewFlg = true;
			this.$forceUpdate();
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

</style>
