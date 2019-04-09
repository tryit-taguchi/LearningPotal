<template>
  <button type="button" v-on="$listeners">
    {{this.text}}
  </button>
</template>

<script>
export default {
  props: {
    text: String
  }
}
</script>

<style lang="scss">
button{
  display: inline-block;
  appearance: none;
  padding: .2em;
  border: none;
  border-radius: 5px;
  font-size: 2.0rem;
  line-height: 1.2;
  color: #fff;
  background-color: #666;
  cursor: pointer;
  text-align: center;
  text-decoration: none;
  // &.primary{
  //   background-color: $primary;
  // }
  // &.large{
  //   font-size: 2.0rem;
  //   line-height: 1;
  //   padding: .5em 1em;
  // }
}
</style>
