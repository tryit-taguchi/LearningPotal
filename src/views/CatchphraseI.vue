<template>
  <div>
    <page-title>
      一言キャッチフレーズ
    </page-title>
    <p class="bulb" style="margin: 20px 100px;">あなたの回答を選択してください</p>
    <textarea-balloon name="CATCHWORD_STR" id="CATCHWORD_STR" value=""></textarea-balloon>
    <div style="text-align:center;margin:-20px auto 20px;">
      <img src="@/assets/catchphrase.png" style="width:624px;height:auto;" alt="">
    </div>
    <div class="button-area">
      <base-button text="投稿" @click="nextPage" />
    </div>
  </div>
</template>

<script>
export default {
  data: function(){
    return {}
  },
  methods: {
    nextPage: function () {
      alert("次のページへ")
    },
  }
}
</script>

<style lang="scss">
.bulb{
  background-image: url(../assets/icon_bulb.png);
  background-size: 50px 50px;
  background-repeat: no-repeat;
  background-position: left center;
  margin: 5px 0;
  padding-left: 50px + 10px;
  min-height: 50px;
  display: flex;
  // justify-content: center;
  align-items: center;
}
</style>