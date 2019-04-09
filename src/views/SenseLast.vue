<template>
  <div>
    <page-title before-text="まとめ２">
      <template v-slot:left><span style="font-size:1.4em">Q</span>uestion<span  style="font-size:2.0em">{{current+1}}</span></template>
      {{questions[current].text}}
    </page-title>
    Q10 3つ選ぶ
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
  
</style>