<template>
  <div>
    <template v-for="(question, index) in questions">
      <page-title before-title="まとめ① 回答（棒グラフ）">
        <template v-slot:left><span class="page-title-lt-l">Q</span>uestion<span class="page-title-lt-xl">{{index+1}}</span></template>
        {{questions[index].text}}
      </page-title>
      <p style="font-size: 3rem;margin:0;text-align:center;">ここに結果グラフ</p>
    </template>
  </div>
</template>

<script>
export default {
  data: function () {
    return {
      current: 0,
      questions: [
        {
          text: '現行デイズ、この１年でだいたい何台売った？',
          answers: ['0台','1～5台','6～9台','10台以上']
        },
        {
          text: '新型デイズが出たら、年間何台売る自信がある？',
          answers: ['1～3台','4～6台','7～9台','10～12台','13～15台','16台以上']
        },
        {
          text: '現行デイズがワゴンRと競合したらどう思う？',
          answers: ['全戦全勝！必ず勝つ','勝てる気がする','五分五分','まずい・困ったと思う','スルーして別のクルマをすすめる']
        },
        {
          text: '現行デイズがN-WGNと競合したらどう思う？',
          answers: ['全戦全勝！必ず勝つ','勝てる気がする','五分五分','まずい・困ったと思う','スルーして別のクルマをすすめる']
        }
      ],
      userSelects: [null, null, null, null]
    }
  },
  methods: {
    nextQuestion: function(e){
      // if(this.userSelects[this.current]!==null){
      //   this.current++;
      // }else{
      //   alert('回答を選択してください');
      // }
    }
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
