<template>
  <div>
    <page-title before-title="オリエンテーション">
      <template v-slot:left><span class="page-title-lt-l">Q</span>uestion<span class="page-title-lt-xl">{{current+1}}</span></template>
      {{questions[current].text}}
    </page-title>
    <div style="padding-left:200px;">
      <p class="bulb">あなたの回答を選択してください</p>
      <div class="radio-block-list">
        <template v-for="(answer, index) in questions[current].answers">
          <input type="radio" v-model="userSelects[current]" :name="'Q_'+current" :id="'Q_'+current+'_'+index" :value="index">
          <label :for="'Q_'+current+'_'+index"><span>{{index+1}}</span><span>{{answer}}</span></label>
        </template>
      </div>
    </div>
    <p style="font-size: 3rem;margin:0;text-align:center;">{{userSelects}}</p>
    <div style="text-align:right;">
      <input type="button" value="前へ" @click="prevQuestion" v-if="current>0">
      <input type="button" value="回答" @click="nextQuestion">
    </div>
  </div>
</template>

<script>
export default {
//	mixins: [Mixin],
  data: function () {
    return {
      current: 0,
      questions: [],
      userSelects: [null, null, null, null]
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
    collback_QuestionsLoad: function(data) {
			this.questions = data;
    }
  },
	mounted: function () {
		this.getJson('http://subcontract.t4u.bz/rest/orientation',this.collback_QuestionsLoad);
	}
}
</script>

<style lang="scss">
.radio-block-list {
  font-size: 3rem;
}
.radio-block-list input[type="radio"] {
  display: none;
}
.radio-block-list label {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  cursor: pointer;
  vertical-align: middle;
  margin-bottom: 5px;
}
.radio-block-list label span:nth-child(1) {
  -webkit-box-flex: 0;
      -ms-flex: 0 1 calc(1024px - 720px);
          flex: 0 1 calc(1024px - 720px);
  background-color: #666;
  margin-left: auto;
  text-align: center;
  color: #fff;
  padding: 5px;
}
.radio-block-list label span:nth-child(2) {
  -webkit-box-flex: 0;
      -ms-flex: 0 0 720px;
          flex: 0 0 720px;
  background-color: #EFEFEF;
  color: #333;
  padding: 5px;
}
.radio-block-list input[type="radio"]:checked + label {
  color: #EB4D4B;
}
.radio-block-list input[type="radio"]:checked + label span:nth-child(1) {
  background-color: #f07a79;
}
.radio-block-list input[type="radio"]:checked + label span:nth-child(2) {
  background-color: #EB4D4B;
  color: #fff;
}
</style>
