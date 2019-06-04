<template>
  <div>
    <page-title before-text="まとめ② 回答一覧（棒グラフ）（みんなの声用）">
      <template v-slot:left><span style="font-size:1.4em">Q</span>uestion<span  style="font-size:2.0em">{{current+1}}</span></template>
      {{questions[current].text}}
    </page-title>
    <bar-chart-result v-if="chartViewFlg" :width="824" :height="450" :chart-data="question" show-your-select />
    <button-area>
      <base-button text="前へ" @click="prevQuestion" v-if="current>0" />
      <base-button text="回答" @click="nextQuestion" />
    </button-area>
  </div>
</template>

<script>
export default {
  data: function () {
    return {
      current: 0,
      questions: [{}],
      userSelects: [null, null, null, null],
      chartViewFlg: true,
      question: {
        "QUESTION_ID":"10",
        "QUESTION_STR":"新型デイズの「売り・気に入った点」は？（３つ答えて）",
        "QUESTION_NO":"1",
        "QUESTION_CNT":"1",
        "ANSWER_CNT":"10",
        "answerList":["走り","デザイン（内外装）","広さ（居室・荷室）","収納","カラー","シート","安全装備","プロパイロット","女性に優しい機能・装備","その他"],
        "selectedNo":8,
        "selectedNoList":[4,6,8],
        "siteValueList":[10,8,15,7,15,6,12,9,12,5],
        "siteSumList":["10","8","15","7","15","6","12","9","12","5"],
        "totalValueList":[6,12,11,6,20,5,10,8,9,12],
        "totalSumList":["6","12","11","6","20","5","10","8","9","12"],
        "questionStr":"Q1. 新型デイズの「売り・気に入った点」は？（３つ答えて）"
      }
    }
  },
  methods: {
    prevQuestion: function(e){
      this.current--;
    },
    nextQuestion: function(e){
      if(this.userSelects[this.current]!==null){
        if(this.current>=3){
          this.$router.push('sensebefore1result');
        }else{
          this.current++;
        }
      }else{
        alert('回答を選択してください');
      }
    },
    collback_QuestionsLoad: function(response) {
			this.questions = response.data;
    },
    collback_AnswerPost: function(response) {
			console.log(response.data);
    }
  },
	mounted: function () {
		//this.$cookies.set('loginId', '124');
		// console.log(this.$cookies.get('loginId'));
		// console.log(this.$cookies.get('PHPSESSID'));
		// console.log(this.$cookies.get('LOGIN_DATE'));
		//Cookies.set('name','value', { expires: 0.5 });
		this.getJson(this.getAPIPath()+'/questions_1',this.collback_QuestionsLoad);
		this.postJson(this.getAPIPath()+'/questions_1',this.collback_AnswerPost);
	}
}
</script>

<style lang="scss">

</style>
