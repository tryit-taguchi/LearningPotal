<template>
  <div class="reporting-radio-header">
    <span>良い</span>
    <span>悪い</span>
  </div>
</template>

<script>
export default {

}
</script>

<style lang="scss">
.reporting-radio-header{
  display: flex;
  justify-content: space-between;
  width: 720px;
  margin-left: auto;
  position: relative;
  padding-bottom: 20px;
  &::after{
    content: "";
    display: block;
    height: 10px;
    position: absolute;
    right: 80px;
    bottom: 5px;
    left: 80px;
    background-image: linear-gradient(to right, #EB4D4B, lighten(#EB4D4B, 30%));
  }
  span:nth-child(1){
    display: block;
    margin-left: 50px;
    &::before{
      content: "";
      display: block;
      width: 0;
      height: 0;
      border-right: 40px solid #EB4D4B;
      border-top: 10px solid transparent;
      border-bottom: 10px solid transparent;
      border-left: none;
      position: absolute;
      bottom: 0;
      left: 50px;
    }
  }
  span:nth-child(2){
    display: block;
    margin-right: 50px;
    &::before{
      content: "";
      display: block;
      width: 0;
      height: 0;
      border-left: 40px solid lighten(#EB4D4B, 30%);
      border-top: 10px solid transparent;
      border-bottom: 10px solid transparent;
      border-right: none;
      position: absolute;
      right: 50px;
      bottom: 0;
    }
  }
}

</style>
