<template>
  <p class="bulb"><slot></slot></p>
</template>

<script>
export default {
  data(){
    return{
    }
  }
}
</script>

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
</style>
