<template>
	<div v-if="questionViewFlg">
		<page-title>
			{{questionName}} 確認
		</page-title>
		<p>{{questionName}}回答はこちらで良いですか？</p>
		<div style="padding-left:50px;">
			<template v-for="question in questionList">
				<question-title type="simple" :theme="questionTitleTheme">Question<span style="font-size: 2em;">{{question.QUESTION_NO}}</span></question-title>
				<radio-block-list-confirm style="padding-left:150px;" :labels="question.answerList" :name="'Q_'+question.QUESTION_NO" v-model="question.selectedNo"/>
			</template>
		</div>
		<button-area>
			<base-button text="回答し直す" background-color="#c3002f" @click="prevPage" />
			<base-button text="上記で回答する" @click="nextPage" />
		</button-area>
	</div>
</template>

<script>
export default {
	data: function(){
		return {
			questionTitleTheme: {
				color: 'black',
				backgroundColor: 'white'
			},
			pageType: 'enquetes_1',
			questionNo: 1,
			questionList: [],
			questionName: "",
			questionViewFlg: false, // データセット後に描画を行う
			freeComment: ""
		}
	},
	created: function () {
		console.log('-- '+this.pageType+'_c');
		// セッション情報の取得等
		this.isLogin();
		this.startSession(this.callback_getSession);
	},
	// メソッド群
	methods: {
		// 前ページへ
		prevPage: function(e){
			this.jump({ name: this.pageType+'_q' });
		},
		// 次ページへ
		nextPage: function(e){
			this.$store.commit('completedQuestion', this.pageType)
			this.jump({ name: this.pageType+'_f' });
		},
		// -- サーバサイドからのコールバック
		// セッション読み込み後
		callback_getSession: function() {
			// セッションを読み込み終わって状態を取得したら問題データを読み込む
			this.getJson(this.getAPIPath()+'/'+this.pageType + '_q/' + this.getMemberId(),this.collback_getData);
			this.questionName = this.$store.state.session.question_atr[this.pageType].QUESTION_NAME;
		},
		// 問題データ取得後
		collback_getData: function(response) {
			this.questionList = response.data.questionList;
			for( var qno in this.questionList ) {
				var question = this.questionList[qno];
				for( var ano in question.answerList ) {
					if( question.selectedNo != ano ) {
						this.questionList[qno].answerList[ano] = null;
					}
				}
				this.questionList[qno].selectedNo = -1;
			}


			this.questionViewFlg = true;
		},
	},
}
</script>

<style lang="scss">
	
</style>