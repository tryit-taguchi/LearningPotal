<template>
  <div v-if="pageViewFlg">
    <main class="question-layout">
      <div style="width:900px;margin-left:50px;">
        <h1>設定画面</h1>
      </div>
      <br>
      <form @submit.prevent="submit">


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
		validation: function () {
			return true;
		},
		// Submit
		submit: function () {
			if( this.validation() ) {
				this.$router.push("/"); // ホームに遷移
			}
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
			console.log(this.configData);
			this.pageViewFlg = true;
		},
	}
}
</script>
