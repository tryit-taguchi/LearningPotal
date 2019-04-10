<template>
  <div>
    <page-title before-text="まとめ１">
      <template v-slot:left><span style="font-size:1.4em">Q</span>uestion<span  style="font-size:2.0em">{{questions[current].number}}</span></template>
      {{questions[current].question}}
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
//	mixins: [Mixin],
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
    }
  },
	mounted: function () {
		this.getJson('http://subcontract.t4u.bz/rest/qa1',this.collback_QuestionsLoad);
	}
}
</script>
<style lang="scss">
  
</style>