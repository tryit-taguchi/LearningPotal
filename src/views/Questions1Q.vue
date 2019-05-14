<template>
  <div>
    <page-title before-text="オリエンテーション 質問（選択）">
      <template v-slot:left><span style="font-size:1.4em">Q</span>uestion<span  style="font-size:2.0em">{{question.QUESTION_NO}}</span></template>
      {{question.QUESTION_STR}}
    </page-title>
    <div style="padding-left:200px;">
      <p class="bulb">あなたの回答を選択してください</p>
      <radio-block-list :labels="question.answers" :name="'Q_'+questionNo" v-model="question.selectedNo" :key="'radiobox'+questionNo" />
    </div>
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
			pageType: 'questions_1',
			questionNo: 1,
			question: {},
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
			return true;
		},
		// 前ページへ
		prevPage: function(e){
			this.questionNo--;
			//this.$router.push({ name: this.pageType+'_a' });
			this.jump({ name: this.pageType+'_a' });
		},
		// 回答
		nextPage: function(e){
			if( this.validation() ) {
				var form = {
						"selectedNo" : this.question.selectedNo,
					};
				console.log("memberId : "+this.getMemberId());
				this.submit(process.env.VUE_APP_API_URL_BASE+'/'+this.pageType+'/' + this.questionNo+'/' + this.getMemberId(),form,this.collback_postData);
			}
		},
		// -- サーバサイドからのコールバック
		// セッション読み込み後
		callback_getSession: function() {
			console.log("セッションを読み込み終わって状態を取得したら問題データを読み込む");
			this.$parent.questionNo = this.$parent.session.question_atr[this.pageType].currentQuestionNo;
			this.getJson(process.env.VUE_APP_API_URL_BASE+'/'+this.pageType+'/' + this.questionNo+'/' + this.getMemberId(),this.collback_getData);
			this.questionName = this.$parent.session.question_atr[this.pageType].QUESTION_NAME;
		},
		// 問題データ取得後
		collback_getData: function(response) {
			this.question = response.data;
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
