<template>
  <form @submit.prevent="submit">
    <div>
      誓約書
      <input type="checkbox" id="agreeCheck" v-model="agreeCheck">
      <input type="submit" value="始める">
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
			agreeCheck: false
		}
	},
	// 初回処理
	mounted: function () {
		this.isLogin(); // ログインチェック
	},
	// メソッド群
	methods: {
		// バリデーション
		validation: function () {
			if( this.agreeCheck != true ) {
				this.errorMessage = "チェックを入れて下さい";
				return false;
			}
			return true;
		},
		// Submit
		submit: function () {
			if( this.validation() ) {
				this.$router.push("/"); // ホームに遷移
			}
		},
	}
}
</script>
