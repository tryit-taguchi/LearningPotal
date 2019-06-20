<template>
	<div v-if="pageViewFlg">
		<main class="question-layout">
			<div style="width:900px;margin-left:50px;">
				<h1>設定画面</h1>
			</div>
			<br>
			<form @submit.prevent="submit">
				<h2>有効設定</h2>
				<!-- ON/OFF のラジオボタン -->
				<div v-for="questionType in questionList">
					<config-radio :name="questionType" :type="questionType" :title="statusHome.enableList[questionType].name"  v-model="statusHome.enableList[questionType].value" />
				</div>
				<p>{{errorMessage}}</p>
				<div class="question-button">
					<base-button text="保　存" @click="save" />
				</div>
				<div v-if="infoMessageViewFlg">
					<p>{{infoMessage}}</p>
				</div>
			</form>
			<br>
			<div class="question-button">
				<base-button text="ログアウト" @click="logout" />
			</div>
			<br>
			<div class="question-button">
				<base-button text="本日用データ生成" @click="testData" />
			</div>
		</main>
	</div>
</template>

<script>
export default {
	// データ定義
	data: function(){
		return {
			infoMessage: '',
			infoMessageViewFlg: false,
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
		// 保存
		save: function () {
			this.validation(this.callback_formSubmit);
		},
		// ログアウト
		logout: function () {
			this.toLogout();
			this.jump('/login');
		},
		// -- サーバサイドからのコールバック
		// セッション読み込み後
		callback_getSession: function() {
			// セッションを読み込み終わって状態を取得したら問題データを読み込む
			//console.log(this.getAPIPath()+'/json/config.json');
			//this.getJson(this.getAPIPath()+'/json/config.json',this.collback_getData);
			this.getJson(this.getAPIPath()+'/config',this.collback_getData);
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
			console.log("callback_formSubmit");

			var enableList = {};
			for( var no in this.questionList ) {
				var questionType = this.questionList[no];
				enableList[questionType] = this.statusHome.enableList[questionType].value;
			}
			var form = {};
			form.enableList = enableList;
			console.log(form.enableList);
			this.post(this.getAPIPath()+'/config',form,this.collback_postData);
		},
		// 設定データ送信後
		collback_postData: function(response) {
			this.$store.commit('setServerInfo', {config: response.data})
			this.infoMessage = "保存しました。";
			this.infoMessageViewFlg = true;
			setTimeout(function() {
				this.infoMessageViewFlg = false;
			}.bind(this), 2000);
		},
		// テストデータ生成
		testData: function() {
			// セッションを読み込み終わって状態を取得したら問題データを読み込む
			this.getJson(this.getAPIPath()+'/createtestdata',function () {
				alert("テストデータ用のセッティングをしました。");
			});
		},
	}
}
</script>
