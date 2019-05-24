<template>
  <div>
    <page-title>
      キャッチフレーズ 選出
    </page-title>
    <div style="margin-left: 50px;">
      <p style="font-size:2.8rem;">
        <img src="../assets/icon_crown_gold.png" class="img-in-text" alt="">
        本日のベストを選出します！<br>
        すばらしいと思うものを<b>3つ選択</b>して、「いいね」を押してください。
      </p>
      <div class="checkbox-inline-list col1" style="font-size:2.0rem;margin:0 60px;">
        <input type="checkbox" name="C[]" id="C_276" value="276">
        <label for="C_276">なんちゃって！</label>
        <input type="checkbox" name="C[]" id="C_277" value="277">
        <label for="C_277">qqq</label>
      </div>
    </div>
    <button-area>
      <base-button text="いいね" @click="" />
    </button-area>
  </div>
</template>

<style lang="scss">
  
.checkbox-inline-list{
  display: flex;
  flex-wrap: wrap;
  border-bottom: 1px solid #fff;
  padding: 5px 0;
  font-size: 3rem;
  &.col1{
    border-bottom: none;
    label{
      flex: 0 0 100%;
      text-align: left;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
  }
  &.col3{
    border-bottom: none;
    label{
      flex: 0 0 calc(100%/3);
      text-align: left;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
  }
  label{
    flex: 0 1 auto;
    text-align: center;
    &::before{
      content: "";
      width: 1em;
      height: 1em;
      display: inline-block;
      border: 2px solid #c3002f;
      border-radius: 2px;
      font-size: 1em;
      line-height: 1;
      vertical-align: middle;
      text-align: center;
      margin-right: .5em;
    }
  }
  input[type="checkbox"]{
    display: none;
  }
  label{
    display: inline-block;
    cursor: pointer;
    vertical-align: middle;
  }
  input[type="checkbox"]:checked + label{
    color: #fff;
    background-color: rgba(0,0,0,0);
    &::before{
      content: "✓";
      color: #fff;
      background-color: #c3002f;
    }
  }
}

</style>