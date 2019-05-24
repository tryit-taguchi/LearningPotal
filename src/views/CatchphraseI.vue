<template>
  <div>
    <page-title>
      一言キャッチフレーズ
    </page-title>
    <bulb-text style="margin: 20px 100px;">
      今回の新型車について、魅力を的確にお客さまに伝えるための一言を皆様から募集します。<br>
      各チームで1つ、お客様の心をつかむフレーズを、自由に記入してください！
    </bulb-text>
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