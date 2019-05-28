<template>
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
/*
			if( this.agreeCheck != true ) {
				this.errorMessage = "チェックを入れて下さい";
				return false;
			}
*/
			return true;
		},
		// Submit
		submit: function () {
console.log("test");
			if( this.validation() ) {
console.log("test2");
				this.$router.push("/"); // ホームに遷移
			}
		},
	}
}
</script>
