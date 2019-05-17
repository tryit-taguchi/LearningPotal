<template>
  <div>
    <page-title before-text="まとめ② 質問（複数選択）">
      <template v-slot:left><span style="font-size:1.4em">Q</span>uestion<span  style="font-size:2.0em">{{10}}</span></template>
      質問文
    </page-title>
    <div style="padding-left:200px;">
      <p class="bulb">あなたの回答を選択してください{{userSelects}}</p>
      <checkbox-block-list :labels="answerList" :name="'cQ_'+questionNo" v-model="userSelects" :key="'checkbox'+questionNo" />
    </div>
    <div style="text-align:right;">
      <base-button text="前へ" @click="prevQuestion" v-if="current>0" />
      <base-button text="回答" @click="nextQuestion" />
    </div>
  </div>
</template>

<script>
export default {
  data: function () {
    return {
      current: 0,
      questions: [{}],
      questionNo: 10,
      userSelects: [1, 2],
      answerList: ['選択肢1','選択肢2','選択肢3','選択肢4','選択肢5','選択肢6','選択肢7','選択肢8','選択肢9','選択肢10']
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
		console.log(this.$cookies.get('loginId'));
		console.log(this.$cookies.get('PHPSESSID'));
		console.log(this.$cookies.get('LOGIN_DATE'));
		//Cookies.set('name','value', { expires: 0.5 });
		this.getJson(process.env.VUE_APP_API_URL_BASE+'/questions_1',this.collback_QuestionsLoad);
		this.postJson(process.env.VUE_APP_API_URL_BASE+'/questions_1',this.collback_AnswerPost);
	}
}
</script>

<style lang="scss">

</style>
