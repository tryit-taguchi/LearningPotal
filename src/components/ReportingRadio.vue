<template>
  <div class="reporting-radio">
    <span class="lt">{{label}}</span>
    <div class="rt">
      <input type="radio" :name="name" :id="name+'_5'" value="5">
      <label :for="name+'_5'">5</label>
      <input type="radio" :name="name" :id="name+'_4'" value="4">
      <label :for="name+'_4'">4</label>
      <input type="radio" :name="name" :id="name+'_3'" value="3">
      <label :for="name+'_3'">3</label>
      <input type="radio" :name="name" :id="name+'_2'" value="2">
      <label :for="name+'_2'">2</label>
      <input type="radio" :name="name" :id="name+'_1'" value="1">
      <label :for="name+'_1'">1</label>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    label: Array,
    name: String,
    value: String
  },
  data() {
    return { vModel: this.value }
  },
  // watch: {
  //   vModel(val) {
  //     this.$emit('input', val);
  //   }
  // }
}
</script>

<style lang="scss">

// color

$primary: #EB4D4B;
$secondary: #7F1787 !default;
$white: #fff !default;
$black: #333 !default;
$gray: #666 !default;

// height size
// ----
//  20 : バー
// 120 : ヘッダー
//  40 : マージン
// 580 : メイン
//   8 : 余り
// 768 : 合計

$header-height: 120px !default;
$header-margin-bottom: 40px !default;
$header-height-content: 120px !default;
$header-content-height: 120px !default;
$main-height: 580px !default;

// z-index

$header-z-index: 100 !default;

// column size

$rt-col-w: 720px;
$lt-col-w: 1024px - $rt-col-w;



.reporting-radio{
  display: flex;
  border-bottom: 1px solid $white;
  padding: 5px 0;
  font-size: 3rem;
  .lt{
    margin-left: auto;
  }
  .rt{
    flex: 0 0 $rt-col-w;
    display: flex;
    justify-content: space-between;
    padding: 0 50px;
  }
  label{
    flex: 0 1 auto;
    text-align: center;
    &::before{
      content: "";
      width: 1em;
      height: 1em;
      display: inline-block;
      border: 2px solid $primary;
      border-radius: 2px;
      font-size: 1em;
      line-height: 1;
      vertical-align: middle;
      text-align: center;
      margin-right: .5em;
    }
  }
  input[type="radio"]{
    display: none;
  }
  label{
    display: inline-block;
    cursor: pointer;
    vertical-align: middle;
  }
  input[type="radio"]:checked + label{
    // color: $white;
    background-color: rgba(0,0,0,0);
    &::before{
      content: "✓";
      color: $white;
      background-color: $primary;
    }
  }
}

</style>
