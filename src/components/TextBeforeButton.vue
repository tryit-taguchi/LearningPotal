<template>
  <p class="button-area-balloon">講師の指示があるまでは<br>「回答」を押さないでください</p>
</template>

<script>
export default {
  data(){
    return{
    }
  }
}
</script>

<style scoped lang="scss">

$_padding: 10px;
$_icon-size: 30px;
.button-area-balloon{
  display: inline-block;
  position: relative;
  align-self: center;
  background-color: rgba(36, 1, 8, 1);
  border: 2px solid #fff;
  border-radius: 4px;
  margin: 0 30px 0 0;
  background-image: url(../assets/icon_exclamation.png);
  background-size: $_icon-size $_icon-size;
  background-repeat: no-repeat;
  background-position: 10px center;
  line-height: 1;
  padding: $_padding $_padding $_padding $_padding*2+$_icon-size;
  &::before,
  &::after{
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
    border-color: transparent transparent transparent #fff;
    right: -20px;
  }
  &::after{
    border-width: 8px 0 8px 17px;
    border-color: transparent transparent transparent rgba(36, 1, 8, 1);
    right: -16px;
  }
}
</style>
