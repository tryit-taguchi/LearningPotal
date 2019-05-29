<template>
  <div class="top-layout" v-if="pageViewFlg">
    <div class="top-visual">
      <img :src="imgTopVisual">
    </div>
    <section class="voice">
      <h2>みんなの声</h2>
      <ul class="voice-list">
        <li><router-link to="/questions_1_r">オリエンテーション</router-link></li>
        <li><router-link to="/catchphrase_r">キャッチフレーズ</router-link></li>
        <li><router-link to="/questions_3_r">まとめ１</router-link></li>
        <li><router-link to="/reporting_all_r">比較 ①、②、③</router-link></li>
        <li><router-link to="/questions_2_r">座学</router-link></li>
        <li><router-link to="/questions_4_r">まとめ２</router-link></li>
      </ul>
    </section>
    <nav class="top-navi">
      <ul>
<!-- 完了したフローは"disabled"クラスを付与する
        <li class="disabled">
          <img src="../assets/icon_navi_04.png" alt="">
            <span class="top-navi-title">オリエンテ<span class="cho">ー</span>ション</span>
        </li>
-->
        <li id="js-navi-questions_1" v-bind:class="{disabled:enableList['questions_1'].value === 0}" @click="naviClick('questions_1')">
          <img src="../assets/icon_navi_01.png" alt="">
          <span class="top-navi-title">オリエンテ<span class="cho">ー</span>ション</span>
        </li>
        <li id="js-navi-reporting_1" v-bind:class="{disabled:enableList['reporting_1'].value === 0}" @click="naviClick('reporting_1')">
          <img src="../assets/icon_navi_02.png" alt="">
          <span class="top-navi-title">試乗１</span>
        </li>
        <li id="js-navi-questions_2" v-bind:class="{disabled:enableList['questions_2'].value === 0}" @click="naviClick('questions_2')">
          <img src="../assets/icon_navi_03.png" alt="">
          <span class="top-navi-title">座学</span>
        </li>
        <li id="js-navi-reporting_2" v-bind:class="{disabled:enableList['reporting_2'].value === 0}" @click="naviClick('reporting_2')">
          <img src="../assets/icon_navi_02.png" alt="">
          <span class="top-navi-title">試乗２</span>
        </li>
        <li id="js-navi-reporting_3" v-bind:class="{disabled:enableList['reporting_3'].value === 0}" @click="naviClick('reporting_3')">
          <img src="../assets/icon_navi_02.png" alt="">
          <span class="top-navi-title">現車・競合車確認</span>
        </li>
        <li id="js-navi-questions_3" v-bind:class="{disabled:enableList['questions_3'].value === 0}" @click="naviClick('questions_3')">
          <img src="../assets/icon_navi_03.png" alt="">
          <span class="top-navi-title">まとめ１</span>
        </li>
        <li id="js-navi-questions_4" v-bind:class="{disabled:enableList['questions_4'].value === 0}" @click="naviClick('questions_4')">
          <img src="../assets/icon_navi_03.png" alt="">
          <span class="top-navi-title">まとめ２</span>
        </li>
        <li id="js-navi-catchphrase" v-bind:class="{disabled:enableList['catchphrase'].value === 0}" @click="naviClick('catchphrase')">
          <img src="../assets/icon_navi_04.png" alt="">
          <span class="top-navi-title">キャッチフレ<span class="cho">ー</span>ズ</span>
        </li>
        <li id="js-navi-faq"         v-bind:class="{disabled:enableList['examinations_1'].value === 0}" @click="naviClick('faq')">
          <img src="../assets/icon_navi_05.png" alt="">
          <span class="top-navi-title">Ｑ＆Ａ</span>
        </li>
        <li id="js-navi-examinations_1" v-bind:class="{disabled:enableList['examinations_1'].value === 0}" @click="naviClick('examinations_1')">
          <img src="../assets/icon_navi_07.png" alt="">
          <span class="top-navi-title">理解度確認テスト</span>
        </li>
        <li id="js-navi-enquetes_1"   v-bind:class="{disabled:enableList['enquetes_1'].value === 0}" @click="naviClick('enquetes_1')">
          <img src="../assets/icon_navi_06.png" alt="">
          <span class="top-navi-title">アンケ<span class="cho">ー</span>ト</span>
        </li>
      </ul>
    </nav>
  </div>
</template>

<script>
export default {
	props: ['imgTopVisual'],
	// データ定義
	data: function(){
		return {
			pageViewFlg: false, // データセット後に描画を行う
			intervalId: undefined, // 画面切り替え時にポーリングを停止させるために保存するID
			config: {},         // コンフィグデータ（変動的な画面の設定等）
			enableList: {},     // ボタンの有効無効フラグ
		}
	},
	// 初回処理（createdではDOM操作をしない）
	created: function () {
		// セッション情報の取得等
		console.log("Home処理開始");
		this.isLogin(); // ログインチェック・ログインしていたらセッション取得
		this.startSession(this.callback_getSession);
	},
	// メソッド群
	methods: {
		// セッション読み込み後
		callback_getSession: function() {
			console.log("セッション読み込み後");
			this.config = this.$parent.serverInfo.config; // ローディング直後のコンフィグの初期状態をセット
			this.enableList = this.config.statusHome.enableList;
			// コンフィグ定期読み込み処理
			this.intervalId = setInterval(function() {
				this.getJson(this.getAPIPath()+'/json/config.json',function(responce) { // 生成済みのjsonからコンフィグデータを読み込む
					this.config = responce.data; // 3秒おきにコンフィグデータをロード
					this.$parent.serverInfo.config = this.config;
					this.enableList = this.config.statusHome.enableList;
					//console.log(this.enableList); // ボタンの有効・無効状態
				}.bind(this));
			}.bind(this), 3000);
			this.pageViewFlg = true; // 表示を開始する
		},
		// ボタンを押した時の挙動
		naviClick: function(questionType) { // オリエンテーション
			switch(questionType) {
				case 'questions_1':      // オリエンテーション
				case 'reporting_1':      // 試乗①
				case 'questions_2':      // 座学
				case 'reporting_2':      // 試乗②
				case 'reporting_3':      // 現車・競合車確認
				case 'questions_3':      // まとめ①
				case 'questions_4':      // まとめ②
				case 'examinations_1':   // 理解度確認テスト
				case 'enquetes_1':       // アンケート
					if( this.enableList[questionType].value == 1 ) {
						this.jump("/"+questionType+"_q");
					}
					break;
				case 'catchphrase':      // キャッチフレーズ
				case 'faq':              // 勉強会Q&A
					if( this.enableList[questionType].value == 1 ) {
						this.jump("/"+questionType);
					}
					break;
			}
//			console.log(questionType);
		},
/*
		questions_1: function() { // 試乗①
			this.jump("/questions_1_q");
		},
				'questions_1',      // オリエンテーション
				'reporting_1',      // 試乗①
				'questions_2',      // 座学
				'reporting_2',      // 試乗②
				'reporting_3',      // 現車・競合車確認
				'questions_3',      // まとめ①
				'questions_4',      // まとめ②
				'examinations_1',   // 理解度確認テスト
				'enquetes_1',       // アンケート
*/
	},
	computed: {
	},
	beforeDestroy: function () {
		console.log('clearInterval');
		clearInterval(this.intervalId); // 画面が変わったらインターバルを外す
	},
}
</script>

<style lang="scss">
//*****************************/
// Top Page Layout
//*****************************/

.top-layout{
  height: 618px;
  display: grid;
  grid-template-columns: 1fr 1fr;
  grid-template-rows: 330px 1fr;
  grid-gap: 10px 0;
  margin-top: 130px;
}

//*****************************/
// Visual
//*****************************/

.top-visual{
  position: relative;
  width: 476px;
  height: 330px;
  // background-image: url(../assets/top_visual.png);
  background-size: contain;
  background-repeat: no-repeat;
  background-position: center center;
  justify-self: center;
  &-text{
    font-size: 1.6rem;
    color: #333;
    font-weight: bold;
    display: block;
    position: absolute;
    top: 160px;
    left: 220px;
  }
  img{
    width: 476px;
    height: 330px;
    object-fit: contain;
  }
}

//*****************************/
// Voice
//*****************************/

.voice{
  position: relative;
  box-sizing: border-box;
  background-image: url(../assets/voice_bg.png);
  background-size: contain;
  background-repeat: no-repeat;
  background-position: top left;
  width: 500px;
  height: 330px;
  h2{
    position: absolute;
    color: #fff;
    margin: 0;
    font-size: 2.8rem;
    line-height: 50px;
    top: 0;
    left: 25px;
  }
  &-list{
    list-style: none;
    margin: 0;
    border-top: none;
    padding: 20px 0;
    li{
      font-size: 1.4rem;
      position: absolute;
      a{
        display: flex;
        justify-content: center;
        align-items: center;
        text-decoration: none;
        height: 100%;
        color: #333;
        font-weight: bold;
        box-sizing: border-box;
      }
      &:nth-child(1){ // オリエンテーション
        // background-color: #f00;
        top: 69px;
        left: 6px;
        width: 168px;
        height: 76px;
        z-index: 6;
        a{
          padding: 3px 3px 18px;
        }
      }
      &:nth-child(2){ // キャッチフレーズ
        // background-color: #0f0;
        top: 154px;
        left: 312px;
        width: 169px;
        height: 78px;
        z-index: 5;
        a{
          padding: 3px 3px 19px;
        }
      }
      &:nth-child(3){ // まとめ１
        // background-color: #00f;
        top: 155px;
        left: 29px;
        width: 123px;
        height: 75px;
        z-index: 4;
        text-align: center;
        a{
          padding: 3px 3px 17px;
        }
      }
      &:nth-child(4){ // 比較①、②、③
        // background-color: #0ff;
        top: 69px;
        left: 322px;
        width: 150px;
        height: 77px;
        z-index: 3;
        a{
          padding: 3px 3px 19px;
        }
      }
      &:nth-child(5){ // 座学
        // background-color: #f0f;
        top: 68px;
        left: 183px;
        width: 122px;
        height: 84px;
        z-index: 2;
        a{
          padding: 3px 3px 25px;
        }
      }
      &:nth-child(6){ // まとめ２
        // background-color: #ff0;
        top: 154px;
        left: 182px;
        width: 125px;
        height: 78px;
        z-index: 1;
        text-align: center;
        a{
          padding: 3px 3px 19px;
        }
      }
    }
  }
}

//*****************************/
// Top Navi
//*****************************/

$top-navi-colors: (
  1: #EB4D4B,
  2: #0C95BA,
  3: #15B28F,
  4: #BABA27,
  5: #AF7500,
  6: #1D64AA,
  7: #2A6B69,
  8: #C766CC,
  9: #7942AD,
  10: #84817A,
  11: #595959
);

.top-navi{
  $_navi-w: 85px;
  $_navi-w-half: $_navi-w / 2;
  grid-column: span 2;
  ul{
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    height: 100%;
  }
  li{
    width: $_navi-w;
    position: relative;
    color: #1A1A1A;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    align-items: center;
    background-color: #EFEFEF;
    box-shadow: inset 0 0 0 1px #999;
    position: relative;
    a{
      display: flex;
      flex-direction: column;
      align-items: center;
      text-decoration: none;
      color: #1A1A1A;
    }
    +li{
      margin-left: 5px;
    }
    &::before{
      content: '';
      display: block;
      position: absolute;
      top: 0;
      left: 0;
      width: 85px;
      height: 58px;
      background-color: #999; // default
      // 116
      // 34
    }
    &::after{
      content: '';
      display: block;
      position: absolute;
      top: 58px;
      left: 0;
      width: 0;
      height: 0;
      border-top: 17px solid #999; // default
      border-right: 42.5px solid transparent;
      border-left: 42.5px solid transparent;
    }
    img{
      width: 85px;
      height: 75px;
      z-index: 1;
    }
    @each $i, $color in $top-navi-colors{
      &:nth-child(#{$i})::before{
        background-color: $color;
      }
      &:nth-child(#{$i})::after{
        border-top-color: $color;
      }
    }
    &.disabled{
      cursor: not-allowed;
      color: #999;
      &::before{
        background-color: #999;
      }
      &::after{
        border-top-color: #999;
      }
      img{
        opacity: .7;
      }
    }
  }
  &-title{
    display: block;
    flex: 1 0 auto;
    width: 100%;
    font-size: 1.6rem;
    line-height: 92px;
    font-weight: bold;
    letter-spacing: .2em;
    writing-mode: vertical-rl;
    margin: 1em 0 .5em;
    .cho{
      display:inline-block;
      transform: rotate(90deg) translate(3px,-3px);
    }
  }
}

</style>
