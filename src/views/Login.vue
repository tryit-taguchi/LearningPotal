<template>
  <form @submit.prevent="submit">
    <div>
      ログイン画面（席番号入力）
      <input type="text" v-model="SEAT_CD" placeholder="座席番号">
      <input type="submit" value="Login">
      <p>{{errorMessage}}</p>
    </div>
  </form>
</template>

<style lang="scss">
  
</style>

<script>
export default {
	// データ定義
	data: function(){
		return {
			errorMessage: '',
			SEAT_CD: process.env.VUE_APP_DEFAULT_SEAT_CD
		}
	},
	// 初回処理
	mounted: function () {
	},
	// メソッド群
	methods: {
		// バリデーション
		validation: function () {
			if( this.SEAT_CD == "" ) {
				this.errorMessage = "座席番号を入力してください。";
				return false;
			}
			return true;
		},
		// Submit
		submit: function () {
			if( this.validation() ) {
				let params = new FormData();
				params.append("SEAT_CD",this.SEAT_CD);
				this.postJson(process.env.VUE_APP_API_URL_BASE+'/login',params,this.collback);
			}
		},
		// サーバサイドからのコールバック
		collback: function(response) {
			this.login = response.data;
			if( this.login != null ) {
				// ログイン情報をクッキーに保存
				this.toLogin(this.login);
				this.$router.push({ name: 'agreement' }); // ログインできたら誓約書画面に遷移
				//console.log("登録されている受講者です");
			} else {
				// 違ってたらログアウトさせる
				this.toLogout();
				this.errorMessage = "登録されていない受講者です。";
			}
		},
	}
}
</script>
