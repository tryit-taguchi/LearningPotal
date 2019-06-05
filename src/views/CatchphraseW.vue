<template>
  <div>
    <page-title>
      一言キャッチフレーズ
    </page-title>
    <p style="font-size:3.6rem;text-align:center;margin:20px auto;">
      ありがとうございました。<br>
      投稿が出そろうまでお待ちください。
    </p>
    <div style="text-align:center;margin:20px auto;">
      <img src="@/assets/catchphrase_wait.png" style="width:824px;height:auto;" alt="">
    </div>
    <button-area>
      <text-before-button>講師の指示があるまでは<br>「進む」を押さないでください</text-before-button>
      <base-button text="進む" @click="nextPage" />
    </button-area>
    <p>
      投稿されたキャッチフレーズリスト（リアルタイム）
    </p>
    <div v-for="catchphrase in catchphraseList">
      {{catchphrase.UPD_DT}} / {{catchphrase.MEMBER_NAME}}さん / {{catchphrase.CATCHPHRASE_STR}}<br>
    </div>

  </div>
</template>

<script>
export default {
	data: function(){
		return {
			pageType: 'catchphrase',
			intervalId: undefined, // 画面切り替え時にポーリングを停止させるために保存するID
			catchphraseList: [],
		}
	},
	// 初回処理（createdではDOM操作をしない）
	created: function () {
		// セッション情報の取得等
		console.log("キャッチフレーズ待ち処理開始");
		this.isLogin(); // ログインチェック・ログインしていたらセッション取得
		this.startSession(this.callback_getSession);
	},
	methods: {
		// セッション読み込み後
		callback_getSession: function() {
			//console.log("セッション読み込み後");
			// 投稿定期読み込み処理
			this.intervalId = setInterval(function() {
				this.getJson(this.getAPIPath()+'/'+this.pageType+'_w/' + this.getMemberId(),function(response) { // 生成済みのjsonからコンフィグデータを読み込む
					this.catchphraseList = response.data.catchphraseList;
				}.bind(this));
			}.bind(this), 3000);
		},
		nextPage: function () {
			this.jump({ name: this.pageType+'_s' });
		},
	},
	beforeDestroy: function () {
		//console.log('clearInterval');
		clearInterval(this.intervalId); // 画面が変わったらインターバルを外す
	},
}
</script>

<style lang="scss">
  
</style>