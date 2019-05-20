<template>
  <div>
    <page-title>
      一言キャッチフレーズ
    </page-title>
    <bulb-text style="margin: 20px 100px;">あなたの回答を選択してください</bulb-text>
    <textarea-balloon name="CATCHWORD_STR" id="CATCHWORD_STR" value=""></textarea-balloon>
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

</style>