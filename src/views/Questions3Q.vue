<template>
  <div>
    <div v-for="question in questionList" key="question1">
      <page-title :before-text="questionName">
        <template v-slot:left><span style="font-size:1.4em">Q</span>uestion<span  style="font-size:2.0em">{{question.QUESTION_NO}}</span></template>
        {{question.QUESTION_STR}}
      </page-title>
      <div style="padding-left:200px;">
        <p class="bulb">あなたの回答を選択してください</p>
        <radio-block-list :labels="question.answerList" :name="'Q_'+questionNo" v-model="question.selectedNo" :key="'radiobox'+questionNo" />
      </div>
      <button-area>
        <!--<base-button text="前へ" @click="prevPage" v-if="questionNo>1" />-->
        <base-button text="回答" @click="nextPage" />
      </button-area>
    </div>
  </div>
</template>

<script>
export default {
	// データ定義
	data: function(){
		return {
			pageType: 'questions_3',
			questionNo: 1,
			questionList: [],
			questionName: "",
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
				this.submit(this.getAPIPath()+'/'+this.pageType + '/' + this.getMemberId() + '/' + this.questionNo,form,this.collback_postData);
			}
		},
		// -- サーバサイドからのコールバック
		// セッション読み込み後
		callback_getSession: function() {
			// セッションを読み込み終わって状態を取得したら問題データを読み込む
			this.questionNo = this.$parent.session.question_atr[this.pageType].currentQuestionNo;
			this.getJson(this.getAPIPath()+'/'+this.pageType + '_q/' + this.getMemberId() + '/' + this.questionNo,this.collback_getData);
			this.questionName = this.$parent.session.question_atr[this.pageType].QUESTION_NAME;
		},
		// 問題データ取得後
		collback_getData: function(response) {
			this.questionList = response.data;
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
<!--
// 問題データの取得例
array (
  'QUESTION_STR' => '現行デイズ、この１年でだいたい何台売った？',
  'QUESTION_NO' => '1',
  'QUESTION_CNT' => '4',
  'ANSWER_CNT' => '4',
  'answers' =>
  array (
    0 => '0台',
    1 => '1～5台',
    2 => '6～9台',
    3 => '10台以上',
  ),
  'selectedNo' => '1',
)
-->
