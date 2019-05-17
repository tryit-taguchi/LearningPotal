<template>
  <div class="checkbox-block-list">
    <template v-for="(label, index) in labels">
      <input
        type="checkbox"
        :name="name"
        :id="name+'_'+index"
        :value="index"
        :checked="reValue.includes(index)"
        @change="$emit('input', reValue)"
        v-model="reValue"
      >
      <label :for="name+'_'+index">
        <span>{{index+1}}</span>
        <span>{{label}}</span>
      </label>
    </template>
  </div>
</template>

<script>
export default {
  data(){
    return{
      // props から渡された value をそのまま使うと[Warning]を吐かれるため、props で受け取った value を reValue に複製してこっちを使う
      reValue: this.value
    }
  },
  props: {
    labels: Array,
    name: String,
    value: Array
  }
}
</script>

<style lang="scss">
.checkbox-block-list{
  font-size: 3rem;
  input[type="checkbox"]{
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
  input[type="checkbox"]:checked + label{
    color: #EB4D4B;
    span:nth-child(1){
      background-color: #620017;
    }
    span:nth-child(2){
      background-color: #c3002f;
      color: #fff;
    }
  }
}
</style>
