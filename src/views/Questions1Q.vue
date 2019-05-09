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
			questionNo: 1,
			question: {
			},
		}
	},
	// 初回処理（createdではDOM操作をしない）
	created: function () {
		this.isLogin(); // ログインチェック
		this.getJson(process.env.VUE_APP_API_URL_BASE+'/questions_1/' + this.questionNo+'/' + this.getMemberId(),this.collback_getData);
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
			this.$router.push({ name: 'questions_1_a' });
		},
		// 回答
		nextPage: function(e){
			if( this.validation() ) {
				let params = new FormData();
				params.append("selectedNo",this.question.selectedNo);
				this.getJson(process.env.VUE_APP_API_URL_BASE+'/questions_1/' + this.questionNo+'/' + this.getMemberId(),this.collback_postData);
			}
		},
		// サーバサイドからのコールバック
		// 
		collback_getData: function(response) {
			console.log("getData");
			this.question = response.data;
		},
		collback_postData: function(response) {
			this.result = response.data;
			if( this.result != null ) {
				this.$router.push({ name: 'questions_1_a' });
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
