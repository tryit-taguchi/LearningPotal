<template>
  <div>
    <page-title :before-text="questionName">
      <template v-slot:left><span style="font-size:1.4em">Q</span>uestion<span  style="font-size:2.0em">{{question.QUESTION_NO}}</span></template>
      {{question.QUESTION_STR}}
    </page-title>
    <bar-chart v-if="chartViewFlg" :width="824" :height="400" :chart-data="chartData" :title="dataObj.questionStr" style="backgroundColor:#fff;width:824px;margin:0 auto;"></bar-chart>
    <div style="text-align:right;">
      <base-button text="前へ" @click="prevPage" />
      <base-button text="回答" @click="nextPage" />
    </div>
  </div>
</template>

<script>
export default {
	// データ定義
	data: function(){
		return {
			pageType: 'questions_1',
			questionNo: 1,
			question: {},
			questionName: "",
			chartViewFlg: false, // データセット後に描画を行う
      // グラフ描画用のデータ群(仮)

      dataObj: {
        questionStr: "Q1. 現行フォレスター、この１年でだいたい何台売った？",
        answerList: ["①0台","②1～5台","③6～9台","④10台以上"],
        valueList: [25,75,0,0],
        sumList:[1,3,0,0],
        selected: 1
      }

//      dataObj: {},
		}
	},
  computed: {
    chartData: function(){
      return {
        labels: this.dataObj.answerList,
        datasets: [
          {
            label: 'あなたの回答',
            data: this.dataObj.valueList.map((v,i)=>(i!==this.dataObj.selected-1)?0:v),
            sum: this.dataObj.sumList,
            backgroundColor: 'rgba(86, 206, 255, 0.2)',
            borderColor: 'rgba(86, 206, 255, 1)',
            borderWidth: 2,
            stack: 'Stack 1'
          },{
            label: 'この会場',
            data: this.dataObj.valueList.map((v,i)=>(i===this.dataObj.selected-1)?0:v),
            sum: this.dataObj.sumList,
            backgroundColor: 'rgba(255, 206, 86, 0.2)',
            borderColor: 'rgba(255, 206, 86, 1)',
            borderWidth: 2,
            stack: 'Stack 1'
          }
        ],
        selectedId: this.dataObj.selected-1 // 「あなたの回答」判別のために加えた独自のプロパティ
      }
    }
  },
	// 初回処理（createdではDOM操作をしない）
	created: function () {
		console.log('-- '+this.pageType+'_a');
		// セッション情報の取得等
		this.isLogin(); // ログインチェック・ログインしていたらセッション取得
		this.startSession(this.callback_getSession);
	},
	// メソッド群
	methods: {
		// バリデーション
		validation: function () {
			return true;
		},
		// 前ページへ
		prevPage: function(e){
			this.questionNo--;
			this.jump({ name: this.pageType+'_q' });
		},
		// 回答
		nextPage: function(e){
			this.$parent.session.question_atr[this.pageType].currentQuestionNo++;
			this.jump({ name: this.pageType+'_q' });
		},
		// -- サーバサイドからのコールバック
		// セッション読み込み後
		callback_getSession: function() {
			// セッションを読み込み終わって状態を取得したら問題データを読み込む
			this.questionNo = this.$parent.session.question_atr[this.pageType].currentQuestionNo;
			this.getJson(process.env.VUE_APP_API_URL_BASE+'/'+this.pageType + '/' + this.getMemberId() + '/' + this.questionNo,this.collback_getData);
			this.questionName = this.$parent.session.question_atr[this.pageType].QUESTION_NAME;
		},
		// 問題データ取得後
		collback_getData: function(response) {
			this.question = response.data;

			this.dataObj.questionStr = "Q11. 現行フォレスター、この１年でだいたい何台売った？";
			this.dataObj.answerList = ["①100台","②1～5台","③6～9台","④10台以上"];
			this.dataObj.valueList = [75,75,0,0];
			this.dataObj.sumList = [3,3,0,0];
			this.dataObj.selected = 1;

			this.chartViewFlg = true;
/*
			this.dataObj.title
        title: "Q1. 現行フォレスター、この１年でだいたい何台売った？",
        label: ["①0台","②1～5台","③6～9台","④10台以上"],
        data: [25,75,0,0],
        sum:[1,3,0,0],
        selected: 1
      }
*/
			this.$forceUpdate();
		},
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
