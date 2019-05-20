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
  padding: 0 1em;
  border: none;
  border-radius: 5px;
  font-size: 2.0rem;
  line-height: 1.2;
  color: #fff;
  background-color: #4472c4;
  cursor: pointer;
  text-align: center;
  text-decoration: none;

  align-self: center;
  height: 50px;

  flex: 0 0 200px;

}
</style>
