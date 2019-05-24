<template>
  <div>
    <page-title>
      理解度確認テスト 確認
    </page-title>
    <p>アンケート回答はこちらで良いですか？</p>
    <div style="padding-left:50px;">
      <question-title type="simple" :theme="questionTitleTheme">Question<span style="font-size: 2em;">1</span></question-title>
      <radio-block-list style="padding-left:150px;" :labels="testLabels[0]" :name="'Q_XXX'" v-model="testSelected[0]" />
      <question-title type="simple" :theme="questionTitleTheme">Question<span style="font-size: 2em;">2</span></question-title>
      <radio-block-list style="padding-left:150px;" :labels="testLabels[1]" :name="'Q_XXX'" v-model="testSelected[0]" />
      <question-title type="simple" :theme="questionTitleTheme">Question<span style="font-size: 2em;">3</span></question-title>
      <radio-block-list style="padding-left:150px;" :labels="testLabels[2]" :name="'Q_XXX'" v-model="testSelected[0]" />
    </div>
    <button-area>
      <base-button text="回答し直す" background-color="#c3002f" @click="" />
      <base-button text="上記で回答する" @click="" />
    </button-area>
  </div>
</template>

<script>
export default {
  data: function(){
    return {
      testLabels: [['選択肢３'],['選択肢Ａ'],['選択肢は']],
      testSelected: [null,2,1],
      questionTitleTheme: {
        color: 'black',
        backgroundColor: 'white'
      }
    }
  }
}
</script>

<style lang="scss">
  
</style>