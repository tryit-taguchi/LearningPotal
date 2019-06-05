<template>
  <span
    :is="is"
    :type="type!=='link'?type:null"
    :href="type==='link'?href:null"
    v-on="$listeners"
    :style="style"
  >
    {{this.text}}
  </span>
</template>

<script>
export default {
  props: {
    text: String,
    backgroundColor: String,
    type: {type: String, default: 'button'},
    href: String
  },
  computed: {
    style: function() {
      const styleObj = {}
      if(this.backgroundColor){
        styleObj['--background-color'] = this.backgroundColor
      }
      return styleObj
    },
    is: function(){
      return this.type==='link' ? 'a' : 'button'
    }
  }
}
</script>

<style scoped lang="scss">
button,a{

  --color: #fff;
  --background-color: #4472c4;

  display: inline-flex;

  appearance: none;
  padding: 0 1em;
  border: none;
  border-radius: 5px;
  font-size: 2.0rem;
  line-height: 1.2;
  color: var(--color);
  background-color: var(--background-color);
  cursor: pointer;
  text-decoration: none;

  align-items: center;
  justify-content: center;
  height: 50px;

  flex: 0 0 200px;

}
button:focus{
  outline: none;
}
</style>
