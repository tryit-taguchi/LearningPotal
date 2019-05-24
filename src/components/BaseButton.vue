<template>
  <button type="button" v-on="$listeners" :style="style">
    {{this.text}}
  </button>
</template>

<script>
export default {
  props: {
    text: String,
    backgroundColor: String
  },
  computed: {
    style: function() {
      const styleObj = {}
      if(this.backgroundColor){
        styleObj['--background-color'] = this.backgroundColor
      }
      return styleObj
    }
  }
}
</script>

<style lang="scss">
button{

  --color: #fff;
  --background-color: #4472c4;

  display: inline-block;

  appearance: none;
  padding: 0 1em;
  border: none;
  border-radius: 5px;
  font-size: 2.0rem;
  line-height: 1.2;
  color: var(--color);
  background-color: var(--background-color);
  cursor: pointer;
  text-align: center;
  text-decoration: none;

  align-self: center;
  height: 50px;

  flex: 0 0 200px;

}
</style>
