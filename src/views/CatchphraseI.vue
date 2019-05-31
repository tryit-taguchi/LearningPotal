<template>
  <div>
    <page-title>
      一言キャッチフレーズ
    </page-title>
    <bulb-text style="margin: 20px 100px;">
      今回の新型車について、魅力を的確にお客さまに伝えるための一言を皆様から募集します。<br>
      各チームで1つ、お客様の心をつかむフレーズを、自由に記入してください！
    </bulb-text>
    <textarea-balloon name="catchPhraseStr" id="catchPhraseStr" value="" v-model="catchPhraseStr"></textarea-balloon>
    <div style="text-align:center;margin:-20px auto 20px;">
      <img src="@/assets/catchphrase.png" style="width:624px;height:auto;" alt="">
    </div>
    <button-area>
      <base-button text="投稿" @click="nextPage" />
    </button-area>
  </div>
</template>

<script>
export default {
	data: function(){
		return {
			pageType: 'catchphrase',
			catchPhraseStr: "",
		}
	},
	methods: {
		// バリデーション
		validation: function (callback) {
			if( this.catchPhraseStr == "" ) {
				this.$modal.show('dialog', {
					text: 'フレーズが未入力です。次のページに進みますか？',
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
		nextPage: function () {
			this.validation(function() {
				var form = {};
				form.catchPhraseStr = this.catchPhraseStr;
				this.submit(this.getAPIPath()+'/'+this.pageType + '_i/' + this.getMemberId(),form, function(response) {
					this.result = response.data;
					if( this.result != null ) {
						this.jump({ name: this.pageType+'_w' });
					} else {
						this.$modal.show('dialog', {
							text: '通信が正常に完了しませんでした。電波の良いところで再度お試し下さい。',
							buttons: [
								{title: 'OK'}
							]
						});
					}
				}.bind(this));
			}.bind(this));
		},
	}
}
</script>

<style lang="scss">

</style>