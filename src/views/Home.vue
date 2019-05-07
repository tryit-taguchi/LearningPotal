<template>
  <div class="top-layout">
    <div class="top-visual">
      <!--<span class="top-visual-text">研修をよりスマートに</span>-->
    </div>
    <section class="voice">
      <h2>みんなの声</h2>
      <ul class="voice-list">
        <li><router-link to="/sensebefore1Result">オリエンテーション</router-link></li>
        <li><router-link to="/sensebefore1Result">キャッチフレーズ</router-link></li>
        <li><router-link to="/sensebefore1Result">まとめ１</router-link></li>
        <li><router-link to="/sensebefore1Result">比較 ①、②、③</router-link></li>
        <li><router-link to="/sensebefore1Result">座学</router-link></li>
        <li><router-link to="/sensebefore1Result">まとめ２</router-link></li>
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
        <li id="js-navi-before1">
          <router-link to="/sensebefore1">
            <img src="../assets/icon_navi_01.png" alt="">
            <span class="top-navi-title">オリエンテ<span class="cho">ー</span>ション</span>
          </router-link>
        </li>
        <li id="js-navi-trial1">
          <router-link to="/trial1">
            <img src="../assets/icon_navi_02.png" alt="">
            <span class="top-navi-title">試乗１</span>
          </router-link>
        </li>
        <li id="js-navi-before2">
          <router-link to="/sensebefore2">
            <img src="../assets/icon_navi_03.png" alt="">
            <span class="top-navi-title">座学</span>
          </router-link>
        </li>
        <li id="js-navi-trial2">
          <router-link to="/trial2">
            <img src="../assets/icon_navi_02.png" alt="">
            <span class="top-navi-title">試乗２</span>
          </router-link>
        </li>
        <li id="js-navi-real">
          <router-link to="/real">
            <img src="../assets/icon_navi_02.png" alt="">
            <span class="top-navi-title">現車・競合車確認</span>
          </router-link>
        </li>
        <li id="js-navi-after">
          <router-link to="/senseafter">
            <img src="../assets/icon_navi_03.png" alt="">
            <span class="top-navi-title">まとめ１</span>
          </router-link>
        </li>
        <li id="js-navi-last">
          <router-link to="/senselast">
            <img src="../assets/icon_navi_03.png" alt="">
            <span class="top-navi-title">まとめ２</span>
          </router-link>
        </li>
        <li id="js-navi-catchword">
          <router-link to="/catchword">
            <img src="../assets/icon_navi_04.png" alt="">
            <span class="top-navi-title">キャッチフレ<span class="cho">ー</span>ズ</span>
          </router-link>
        </li>
        <li>
          <router-link to="/faq">
            <img src="../assets/icon_navi_05.png" alt="">
            <span class="top-navi-title">Ｑ＆Ａ</span>
          </router-link>
        </li>
        <li id="js-navi-understanding">
          <router-link to="/understanding">
            <img src="../assets/icon_navi_07.png" alt="">
            <span class="top-navi-title">理解度確認テスト</span>
          </router-link>
        </li>
        <li id="js-navi-enquete">
          <router-link to="/enquete">
            <img src="../assets/icon_navi_06.png" alt="">
            <span class="top-navi-title">アンケ<span class="cho">ー</span>ト</span>
          </router-link>
        </li>
      </ul>
    </nav>
  </div>
</template>

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
  background-image: url(../assets/top_visual.png);
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

<script>
export default {
  data: function(){
    return {
    }
  }
}
</script>
