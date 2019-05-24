<template>
  <div>
    <page-title>
      アンケート 質問（選択）
    </page-title>
    <div style="padding-left:100px;">
      <bulb-text>あなたの回答を選択してください</bulb-text>
      <template v-for="question in questions">        <question-title>
        <template v-slot:left>Question<span style="font-size:2em">{{question.no}}</span></template>
          {{question.q}}
        </question-title>
        <radio-block-list style="padding-left:150px;" :labels="question.a" :name="'Q_'+question.no" v-model="question.selected" :key="'radio'+question.no" />
      </template>
    </div>
    <h2>フリーコメント（120文字まで）</h2>
    <textarea-balloon name="freeComment" id="freeComment" value="" v-model="freeComment"></textarea-balloon>
    <button-area>
      <text-before-button>講師の指示があるまでは<br>「回答」を押さないでください</text-before-button>
      <base-button text="回答" />
    </button-area>
    <v-dialog/>
  </div>
</template>

<script>
export default {
  data(){
    return {
      // ■データの持ち方は仮
      questions: [
        {
          no: 1,
          q: '勉強会全体の満足度',
          a: ['とても良い','良い','どちらともいえない','悪い','とても悪い'],
          selected: null
        },
        {
          no: 2,
          q: '勉強会全体の満足度',
          a: ['とても良い','良い','どちらともいえない','悪い','とても悪い'],
          selected: null
        },
        {
          no: 3,
          q: '勉強会全体の満足度',
          a: ['とても良い','良い','どちらともいえない','悪い','とても悪い'],
          selected: null
        },
        {
          no: 4,
          q: '勉強会全体の満足度',
          a: ['とても良い','良い','どちらともいえない','悪い','とても悪い'],
          selected: null
        },
        {
          no: 5,
          q: '勉強会全体の満足度',
          a: ['とても良い','良い','どちらともいえない','悪い','とても悪い'],
          selected: null
        }
      ],
      freeComment: ""
    }
  }
}
</script>

<style lang="scss">

</style>
