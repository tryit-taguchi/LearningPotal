<template>
  <div class="radio-block-list">
    <template v-for="(label, index) in labels">
      <input
        type="radio"
        :name="name"
        :id="name+'_'+index"
        :value="index"
        :checked="index===value"
        @change="$emit('input', Number($event.target.value))"
      >
      <label :for="name+'_'+index">
        <span v-if="label!=null">{{index+1}}</span>
        <span v-if="label!=null">{{label}}</span>
      </label>
    </template>
  </div>
</template>

<script>
export default {
  props: {
    labels: Array,
    name: String,
    value: Number
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
      background-color: #620017;
      margin-left: auto;
      text-align: center;
      color: #fff;
      padding: 5px;
    }
    span:nth-child(2){
      flex: 0 0 720px;
      background-color: #fff;
      color: #620017;
      padding: 5px;
    }
  }
  input[type="radio"]:checked + label{
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
