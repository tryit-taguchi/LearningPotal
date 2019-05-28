<template>
  <div v-if="pageViewFlg">
    <main class="question-layout">
      <div style="width:900px;margin-left:50px;">
        <h1>設定画面</h1>
      </div>
      <br>
      <form @submit.prevent="submit">
        <h2>有効設定</h2>
<!--
        {{statusHome.enableList['questions_1'].name}}
        <input type="radio" id="questions_1_on" value="1" v-model="statusHome.enableList['questions_1'].value">
        <label for="questions_1_on">ON</label>
        <input type="radio" id="questions_1_off" value="0" v-model="statusHome.enableList['questions_1'].value">
        <label for="questions_1_off">OFF</label>
        <br>
-->
        <div v-for="questionType in questionList">
          <config-radio :name="questionType" :type="questionType" :title="statusHome.enableList[questionType].name"  v-model="statusHome.enableList[questionType].value" />
        </div>


        <p>{{errorMessage}}</p>
        <div class="question-button">
          <base-button text="保　存" @click="submit" />
        </div>
      </form>
      <br>
    </main>
  </div>
</template>

<style lang="scss">
  
</style>

<script>
export default {
	// データ定義
	data: function(){
		return {
			errorMessage: '',
			configData: {},
			statusHome: {},
			// 並べる順番
			questionList: [
				'questions_1',      // オリエンテーション
				'reporting_1',      // 試乗①
				'questions_2',      // 座学
				'reporting_2',      // 試乗②
				'reporting_3',      // 現車・競合車確認
				'questions_3',      // まとめ①
				'questions_4',      // まとめ②
				'catchphrase',      // キャッチフレーズ
				'faq',              // 勉強会Q&A
				'examinations_1',   // 理解度確認テスト
				'enquetes_1',       // アンケート
			],
			pageViewFlg: false, // データセット後に描画を行う
		}
	},
	// 初回処理（createdではDOM操作をしない）
	created: function () {
		// セッション情報の取得等
		this.isLogin();
		this.startSession(this.callback_getSession);
	},
	// メソッド群
	methods: {
		// バリデーション
		validation: function (callback) {
			callback();
			return true;
		},
		// Submit
		submit: function () {
			this.validation(this.callback_formSubmit);
		},
		// -- サーバサイドからのコールバック
		// セッション読み込み後
		callback_getSession: function() {
			// セッションを読み込み終わって状態を取得したら問題データを読み込む
			//console.log(this.getAPIPath()+'/json/config.json');
			this.getJson(this.getAPIPath()+'/json/config.json',this.collback_getData);
		},
		// Configデータ取得後
		collback_getData: function(response) {
			this.configData = response.data;
			this.statusHome = this.configData.statusHome;
			console.log(this.statusHome.enableList['questions_1'].name);
			this.pageViewFlg = true;
		},
		// フォームのSubmit
		callback_formSubmit: function(e) {
			var enableList = {};
			for( var no in this.questionList ) {
				var questionType = this.questionList[no];
				enableList[questionType] = this.statusHome.enableList[questionType].value;
			}
			var form = {};
			form.enableList = enableList;
			console.log(form.enableList);
			//this.submit(this.getAPIPath()+'/'+this.pageType + '/' + this.getMemberId(),form,this.collback_postData);
		},
	}
}
</script>
