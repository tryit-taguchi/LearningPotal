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