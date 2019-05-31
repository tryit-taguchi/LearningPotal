<template>
  <div v-if="pageViewFlg">
    <page-title>
      キャッチフレーズ 結果
    </page-title>
    <div style="margin-right:100px;margin-left:100px;">
      <p style="font-size:3rem;">投票有難うございます！本日のベスト３は…</p>
      <p style="font-size:3rem;margin:10px auto;">
        <img src="../assets/icon_crown_gold.png" class="img-in-text" alt="">&ensp;Best1&ensp;{{catchphraseRankList[0].CATCHPHRASE_STR}}
      </p>
      <p style="font-size:3rem;margin:10px auto;">
        <img src="../assets/icon_crown_silver.png" class="img-in-text" alt="">&ensp;Best2&ensp;{{catchphraseRankList[1].CATCHPHRASE_STR}}
      </p>
      <p style="font-size:3rem;margin:10px auto;">
        <img src="../assets/icon_crown_bronze.png" class="img-in-text" alt="">&ensp;Best2&ensp;{{catchphraseRankList[2].CATCHPHRASE_STR}}
      </p>

      <p class="catchword-sofar-heading" v-if="catchphraseOldLength!=0">これまでのキャッチフレーズ</p>
      <div v-for="(dateList,areaName) in catchphraseOldList">
        <p style="font-size:3rem;margin:20px auto 10px;">{{areaName}}</p>
        <div v-for="(areaList,CATCHPHRASE_DT) in dateList">
          <ul v-for="CATCHPHRASE_STR in areaList">
            <li style="font-size:3rem;margin-left:50px;">
              {{CATCHPHRASE_STR}}
            </li>
          </ul>
        </div>
      </div>

    </div>
  </div>
</template>


<script>
export default {
	data: function(){
		return {
			pageType: 'catchphrase',
			pageViewFlg: false, // データセット後に描画を行う
			catchphraseRankList: [],
			catchphraseOldList: {},
			catchphraseOldLength: 0,
		}
	},
	created: function () {
		console.log('-- '+this.pageType+'_s');
		// セッション情報の取得等
		this.isLogin();
		this.startSession(function () {
			// セッションを読み込み終わって状態を取得したら問題データを読み込む
			this.getJson(this.getAPIPath()+'/'+this.pageType + '_r/' + this.getMemberId(),function (response) {
				this.catchphraseRankList  = response.data.catchphraseRankList;
				this.catchphraseOldList   = response.data.catchphraseOldList;
				this.catchphraseOldLength = Object.keys(this.catchphraseOldList).length;
				this.pageViewFlg = true;
			}.bind(this));
		}.bind(this));
	},
	methods: {
	}
}
</script>

<style scoped lang="scss">

.catchword-sofar-heading{
  font-size:3rem;
  text-align:center;
  display: flex;
  white-space: nowrap;
  align-items: center;
  &%ba{
    content: "";
    display: block;
    height: 2px;
    background-color: #fff;
    flex: 1 1 100%;
  }
  &::before{
    @extend %ba;
    margin-right: 2rem;
  }
  &::after{
    @extend %ba;
    margin-left: 2rem;
  }
}
</style>