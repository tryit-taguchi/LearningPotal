<template>
  <div v-if="pageViewFlg">
    <page-title>
      キャッチフレーズ 選出
    </page-title>
    <div style="margin-left: 50px;">
      <p style="font-size:2.8rem;">
        <img src="../assets/icon_crown_gold.png" class="img-in-text" alt="">
        本日のベストを選出します！<br>
        すばらしいと思うものを<b>3つ選択</b>して、「いいね」を押してください。
      </p>
      <div v-for="catchphrase in catchphraseList">
        <div class="checkbox-inline-list col1" style="font-size:2.0rem;margin:0 60px;">
          <input type="checkbox" name="catchphraseSelectList[]" :id="'catchphraseSelect_'+catchphrase.CATCHPHRASE_ID" :value="catchphrase.CATCHPHRASE_ID" v-model="checkedIdList">
          <label :for="'catchphraseSelect_'+catchphrase.CATCHPHRASE_ID">{{catchphrase.CATCHPHRASE_STR}}</label>
        </div>
      </div>
    </div>
    <button-area>
      <base-button text="いいね" @click="nextPage" />
    </button-area>
    <v-dialog/>
  </div>
</template>

<script>
export default {
	data: function(){
		return {
			pageType: 'catchphrase',
			pageViewFlg: false, // データセット後に描画を行う
			catchphraseList: [],
			checkedIdList: [],
		}
	},
	created: function () {
		console.log('-- '+this.pageType+'_s');
		// セッション情報の取得等
		this.isLogin();
		this.startSession(function () {
			// セッションを読み込み終わって状態を取得したら問題データを読み込む
			this.getJson(this.getAPIPath()+'/'+this.pageType + '_s/' + this.getMemberId(),function (response) {
				this.catchphraseList = response.data.catchphraseList;
				this.pageViewFlg = true;
			}.bind(this));
		}.bind(this));
	},
	methods: {
		// バリデーション
		validation: function (callback) {
			if( this.checkedIdList.length < 3 ) {
				this.$modal.show('dialog', {
					text: 'フレーズを3つ選択して下さい。',
					buttons: [
						{title: 'OK'}
					]
				});
				return false;
			}
			callback();
			return true;
		},
		nextPage: function () {
			this.validation(function() {
				var form = {};
				form.checkedIdList = this.checkedIdList;
				this.submit(this.getAPIPath()+'/'+this.pageType + '_s/' + this.getMemberId(),form, function(response) {
					this.result = response.data;
					if( this.result != null ) {
						this.jump({ name: this.pageType+'_r' });
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
  
.checkbox-inline-list{
  display: flex;
  flex-wrap: wrap;
  border-bottom: 1px solid #fff;
  padding: 5px 0;
  font-size: 3rem;
  &.col1{
    border-bottom: none;
    label{
      flex: 0 0 100%;
      text-align: left;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
  }
  &.col3{
    border-bottom: none;
    label{
      flex: 0 0 calc(100%/3);
      text-align: left;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
  }
  label{
    flex: 0 1 auto;
    text-align: center;
    &::before{
      content: "";
      width: 1em;
      height: 1em;
      display: inline-block;
      border: 2px solid #c3002f;
      border-radius: 2px;
      font-size: 1em;
      line-height: 1;
      vertical-align: middle;
      text-align: center;
      margin-right: .5em;
    }
  }
  input[type="checkbox"]{
    display: none;
  }
  label{
    display: inline-block;
    cursor: pointer;
    vertical-align: middle;
  }
  input[type="checkbox"]:checked + label{
    color: #fff;
    background-color: rgba(0,0,0,0);
    &::before{
      content: "✓";
      color: #fff;
      background-color: #c3002f;
    }
  }
}

</style>