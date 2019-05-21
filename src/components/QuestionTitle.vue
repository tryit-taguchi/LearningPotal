<template>
  <div class="question-title">
    <span class="simple" v-if="type=='simple'" :style="style"><slot></slot></span>
    <template v-else>
      <span class="lt"><slot name="left"></slot></span>
      <span class="rt"><slot></slot></span>
    </template>
  </div>
</template>

<script>
export default {
  props: {
    type: String,
    color: String,
    theme: Object
  },
  computed: {
    style: function() {
      return {
        '--color': this.theme.color,
        '--background-color': this.theme.backgroundColor
      }
    }
  }
}
</script>

<style scoped lang="scss">
.question-title{
  display: flex;
  height: 60px;
  font-size: 2.8rem;
  margin: 10px 0;
  // &-margin{
  //   position: absolute;
  //   top: -2rem;
  //   font-size: 2rem;
  //   line-height: 1;
  //   background-color: rgba(0,0,0,0);
  //   color: rgba(101,101,101,1);
  // }
  .lt{
    flex: 0 1 30%;
    position: relative;
    color: #fff;
    background-color: #242424;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  .rt{
    flex: 0 0 720px;
    color: #333;
    background-color: #F7F7F7;
    display: flex;
    // justify-content: center;
    align-items: center;
    line-height: 1;
    padding: 0 1em;
  }
  .simple{
    --color: #fff;
    --background-color: #c3002f;
    color: var(--color);
    background-color: var(--background-color);
    display: flex;
    justify-content: center;
    align-items: center;
    margin-right: 720px;
    flex: 1 1 100%;
    border-radius: 5px;
  }
  // &-simple-primary{
  //   color: #fff;
  //   background-color: #c3002f;
  //   display: flex;
  //   justify-content: center;
  //   align-items: center;
  //   margin-right: 720px;
  //   flex: 1 1 100%;
  //   border-radius: 5px;
  // }
  // &-simple-white{
  //   color: #333;
  //   background-color: #fff;
  //   display: flex;
  //   justify-content: center;
  //   align-items: center;
  //   margin-right: 720px;
  //   flex: 1 1 100%;
  //   border-radius: 5px;
  // }
  // &-simple-gray{
  //   color: #fff;
  //   background-color: #666;
  //   display: flex;
  //   justify-content: center;
  //   align-items: center;
  //   margin-right: 720px;
  //   flex: 1 1 100%;
  //   border-radius: 5px;
  // }
  // &-l{
  //   font-size: 1.4em;
  // }
  // &-xl{
  //   font-size: 2.0em;
  // }
}


</style>
