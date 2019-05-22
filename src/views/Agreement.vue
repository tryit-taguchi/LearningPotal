<template>
  <main class="question-layout">
    <div style="width:900px;margin-left:50px;">
      <h1>下記をよくお読みください。</h1>
      <p>
      お読みいただいた後、「制約事項に同意」をチェックしてから「始める」ボタンを押してください。</h1>
      </p>
    </div>
    <p style="text-align:center;font-size:14pt;">
      誓約書
    </p>
    <div style="width:800px;margin-left:100px;font-size:10pt;">
      私は、日産自動車株式会社　人財教育支援部が主催する新商品体験試乗会（以下「本試乗会」という）に参加するにあたり、次の通り誓約致します。<br>
      <br>
      1.  【指示事項の遵守】<br>
      <div style="padding-left:2em">
      私は、主催者の定める注意事項に従い、主催者の指示を遵守します。<br>
      （走行ルール及び立入区域の遵守、カメラ撮影の原則禁止、等）<br>
      </div>
      <br>
      2.  【過失災害の自己責任】<br>
      <div style="padding-left:2em">
      私は、本試乗会において起きた自己の故意、過失に起因する死亡、負傷、その他事故による損害については、自己の責任と負担において解決し、当該事故により私及び私の同伴者が受けた損害については、主催者に対して、責任の追及、及び損害賠償請求を致しません。<br>
      </div>
      <br>
      3.  【過失損害の賠償責任】<br>
      <div style="padding-left:2em">
      私は、自己の故意、過失による事故等に起因して、主催者及び第三者に損害を与えた<br>
      場合、当該損害の賠償を行うものとします。<br>
      </div>
      <br>
      4.  【中止の場合】<br>
      <div style="padding-left:2em">
      私は、天候不順及び事故等により、主催者の判断で本試乗会が中止となった場合でも、中止によって生じる損害、経費等を主催者に対して請求致しません。
      </div>
      <br>
      5.  【秘匿の漏洩】<br>
      <div style="padding-left:2em">
      私は、本試乗会で知り得た情報等に関しては、公知であるものを除き、第三者に開示、漏洩致しません。
      </div>
      <br>
      6.  【肖像等の使用】<br>
      <div style="padding-left:2em">
      私は、本試乗会においてビデオ、写真等に撮影された私の肖像やコメント等が、主催者の教育活動に使用されることを承諾し、かつ、その対価を要求しません。<br>
      </div>
    </div>
    <br>
    <form @submit.prevent="submit">
      <input type="checkbox" id="agreeCheck" v-model="agreeCheck" style="width:40px:height40px;-moz-transform: scale(2); -webkit-transform: scale(2); transform: scale(2);">
      <label for="chkAgree" style="font-size:16pt">私は上記の制約事項に同意いたします</label><br>
      <p>{{errorMessage}}</p>
      <div class="question-button">
        <base-button text="始める" @click="submit" />
      </div>
    </form>
    <br>
  </main>
</template>

<style lang="scss">
  
</style>

<script>
export default {
	// データ定義
	data: function(){
		return {
			errorMessage: '',
			agreeCheck: false
		}
	},
	// 初回処理
	mounted: function () {
		this.isLogin(); // ログインチェック
	},
	// メソッド群
	methods: {
		// バリデーション
		validation: function () {
			if( this.agreeCheck != true ) {
				this.errorMessage = "チェックを入れて下さい";
				return false;
			}
			return true;
		},
		// Submit
		submit: function () {
console.log("test");
			if( this.validation() ) {
console.log("test2");
				this.$router.push("/"); // ホームに遷移
			}
		},
	}
}
</script>
