<template>
  <div>
    <page-title>
      理解度確認テスト 質問（選択）
    </page-title>
    <div style="padding-left:50px;">
      <bulb-text>あなたの回答を選択してください</bulb-text>
      <question-title type="simple">Question<span style="font-size: 2em;">1</span></question-title>
      <radio-block-list style="padding-left:150px;" :labels="testLabels[0]" :name="'Q_XXX'" v-model="testSelected[0]" />
      <question-title type="simple">Question<span style="font-size: 2em;">2</span></question-title>
      <radio-block-list style="padding-left:150px;" :labels="testLabels[1]" :name="'Q_XXX'" v-model="testSelected[0]" />
      <question-title type="simple">Question<span style="font-size: 2em;">3</span></question-title>
      <radio-block-list style="padding-left:150px;" :labels="testLabels[2]" :name="'Q_XXX'" v-model="testSelected[0]" />
    </div>
    <button-area>
      <base-button text="回答を確認する" @click="" />
    </button-area>
  </div>
</template>

<script>
export default {
  data: function(){
    return {
      testLabels: [['選択肢１','選択肢２','選択肢３'],['選択肢Ａ','選択肢Ｂ','選択肢Ｃ'],['選択肢い','選択肢ろ','選択肢は']],
      testSelected: [null,2,1]
    }
  }
}
</script>

<style lang="scss">
  
</style>