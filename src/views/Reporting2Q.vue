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
				<reporting-radio v-for="(answer, index) in question.answerList" :name="'Q_'+question.QUESTION_NO+'_'+index" :label="answer" :max-value="5" v-model="question.selectedNoList[index]" :key="question.QUESTION_NO+'_'+index" />
			</div>
		</div>
		<h2>フリーコメント（120文字まで）</h2>
		<textarea-balloon name="freeComment" id="freeComment" value="" v-model="freeComment"></textarea-balloon>
		<button-area>
			<text-before-button>講師の指示があるまでは<br>「回答」を押さないでください</text-before-button>
			<base-button text="回答" @click="nextPage" />
		</button-area>
		<v-dialog/>
	</div>
</template>

<script>
export default {
	// データ定義
	data: function(){
		return {
			pageType: 'reporting_2',
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
		// -- サーバサイドからのコールバック
		// セッション読み込み後
		callback_getSession: function() {
			// セッションを読み込み終わって状態を取得したら問題データを読み込む
			this.getJson(this.getAPIPath()+'/'+this.pageType + '_q/' + this.getMemberId(),this.collback_getData);
			this.questionName = this.$store.state.session.question_atr[this.pageType].QUESTION_NAME;
			this.questionHtml = this.$store.state.session.question_atr[this.pageType].QUESTION_HTML;
			this.questionExplanation = this.$store.state.session.question_atr[this.pageType].QUESTION_EXPLANATION;
			this.questionMaxValue = this.$store.state.session.question_atr[this.pageType].ANSWER_SELECT_CNT;
		},
		// 問題データ取得後
		collback_getData: function(response) {
			this.questionList = response.data.questionList;
			this.freeComment  = response.data.freeComment;
			this.questionViewFlg = true;
		},
		// 回答
		nextPage: function(e){
			this.validation(this.callback_formSubmit);
		},
		// バリデーション
		validation: function (callback) {
			for( var qno in this.questionList ) {
				for( var ano = 0; ano < this.questionList[qno].ANSWER_CNT; ano++ ) {
					if( this.questionList[qno].selectedNoList[ano] == 0 ) {
						var questionNo = parseInt(qno)+1;
						this.$modal.show('dialog', {
							text: 'Question'+questionNo+'に未回答があります。<br>ご回答のご確認をお願いします。',
							buttons: [
								{title: 'OK'}
							]
						})
						return false;
					}
				}
			}
			if( this.isEmpty(this.freeComment) ) {
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
		// フォームのSubmit
		callback_formSubmit: function(e) {
			var answerList = [];
			for( var no in this.questionList ) {
				answerList.push(this.questionList[no]);
			}
			var form = {};
			form.answerList = answerList;
			form.freeComment = this.freeComment;
			this.$store.commit('completedQuestion', this.pageType)
			this.submit(this.getAPIPath()+'/'+this.pageType + '/' + this.getMemberId(),form,this.collback_postData);
		},
		// 回答データ送信後
		collback_postData: function(response) {
			this.result = response.data;
			if( this.result != null ) {
				this.jump({ name: this.pageType+'_a' });
			} else {
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

