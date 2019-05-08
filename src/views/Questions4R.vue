<template>
  <div>
    <page-title before-text="まとめ② 回答一覧（棒グラフ）（みんなの声用）">
      <template v-slot:left><span style="font-size:1.4em">Q</span>uestion<span  style="font-size:2.0em">{{current+1}}</span></template>
      {{questions[current].text}}
    </page-title>
    <div style="padding-left:200px;">
      <p class="bulb">あなたの回答を選択してください</p>
      <radio-block-list :labels="questions[current].answers" :name="'Q_'+current" v-model="userSelects[current]" :key="'radiobox'+current" />
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
