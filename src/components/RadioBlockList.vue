<template>
  <div class="radio-block-list">
    <template v-for="(label, index) in labels">
      <input type="radio" v-model="vModel" :name="name" :id="name+'_'+index" :value="index">
      <label :for="name+'_'+index"><span>{{index+1}}</span><span>{{label}}</span></label>
    </template>
  </div>
</template>

<script>
export default {
  props: {
    labels: Array,
    name: String,
    value: Number
  },
  data() {
    return { vModel: this.value }
  },
  watch: {
    vModel(val) {
      this.$emit('input', val);
    }
  }
}
</script>

<style lang="scss">
.radio-block-list{
  font-size: 3rem;
  input[type="radio"]{
    display: none;
  }
  label{
    display: flex;
    cursor: pointer;
    vertical-align: middle;
    margin-bottom: 5px;
    span:nth-child(1){
      flex: 0 1 calc(1024px - 720px);
      background-color: #242424;
      margin-left: auto;
      text-align: center;
      color: #fff;
      padding: 5px;
    }
    span:nth-child(2){
      flex: 0 0 720px;
      background-color: #fff;
      color: #000;
      padding: 5px;
    }
  }
  input[type="radio"]:checked + label{
    color: #EB4D4B;
    span:nth-child(1){
      background-color: #f07a79;
    }
    span:nth-child(2){
      background-color: #EB4D4B;
      color: #fff;
    }
  }
}
</style>
