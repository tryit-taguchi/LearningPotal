<template>
  <div class="question-layout">
    <page-title>
      <template v-slot:left>試乗① 質問（選択）<span style="font-size:2.0em">1</span></template>
      新旧比較 現行 vs 新型
    </page-title>
    <div style="padding-left:100px;">
      <p class="bulb">あなたの回答を選択してください</p>
      <question-title>
        <template v-slot:left>Question<span style="font-size:2em">1</span></template>
        0～80km/h加速
      </question-title>
      <reporting-radio-header />
      <reporting-radio name="Q_4_17_0" label="現行" />
      <reporting-radio name="Q_4_17_1" label="新型" />
      <question-title>
        <template v-slot:left>Question<span style="font-size:2em">2</span></template>
        操縦安定性
      </question-title>
      <reporting-radio-header />
      <reporting-radio name="Q_4_18_0" label="現行" />
      <reporting-radio name="Q_4_18_1" label="新型" />
      <question-title>
        <template v-slot:left>Question<span style="font-size:2em">3</span></template>
        乗り心地
      </question-title>
      <reporting-radio-header />
      <reporting-radio name="Q_4_19_0" label="現行" />
      <reporting-radio name="Q_4_19_1" label="新型" />
      <question-title>
        <template v-slot:left>Question<span style="font-size:2em">4</span></template>
        静粛性
      </question-title>
      <reporting-radio-header />
      <reporting-radio name="Q_4_36_0" label="現行" />
      <reporting-radio name="Q_4_36_1" label="新型" />
    </div>
    <h2>フリーコメント（120文字まで）</h2>
    <textarea-balloon name="freeComment" id="freeComment" value=""></textarea-balloon>
    <div class="button-area">
      <p class="button-area-balloon">講師の指示があるまでは<br>「回答」を押さないでください</p>
      <input type="button" id="btnAnswer" value="回答">
    </div>
  </div>
</template>

<style lang="scss">
.bulb{
  background-image: url(../assets/icon_bulb.png);
  background-size: 50px 50px;
  background-repeat: no-repeat;
  background-position: left center;
  margin: 5px 0;
  padding-left: 50px + 10px;
  min-height: 50px;
  display: flex;
  // justify-content: center;
  align-items: center;
}
.button-area{
  $_padding: 10px;
  $_icon-size: 30px;
  display: flex;
  justify-content: flex-end;
  &+&{
    margin-top: 5px;
  }
  &-balloon{
    position: relative;
    align-self: center;
    background-color: #EB4D4B;
    // border: 2px solid #fff;
    border-radius: 4px;
    margin: 0 30px 0 0;
    background-image: url(../assets/icon_exclamation.png);
    background-size: $_icon-size $_icon-size;
    background-repeat: no-repeat;
    background-position: 10px center;
    line-height: 1;
    color: #fff;
    padding: $_padding $_padding $_padding $_padding*2+$_icon-size;
    &::before {
      display: block;
      content: "";
      width: 0;
      height: 0;
      border-style: solid;
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
    }
    &::before{
      border-width: 10px 0 10px 20px;
      border-color: transparent transparent transparent #EB4D4B;
      right: -20px;
    }
    // &::after{
    //  border-width: 8px 0 8px 17px;
    //  border-color: transparent transparent transparent rgba(36, 1, 8, 1);
    //  right: -16px;
    // }
  }
  [type="button"],
  [type="submit"],
  button,
  .button{
    align-self: center;
    background-color: #666;
    height: 50px;
    font-size: 20px;
    color: #fff;
    border: none;
    appearance: none;
    cursor: pointer;
    padding: 0 1em;
    flex: 0 0 200px;
    margin-right: 50px;
  }
  .button-primary{
    background-color: #EB4D4B;
  }
  .button-blue{
    background-color: #0C95BA;
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
