<template>
  <div class="base-switch">
    <input
      v-model="computedValue"
      type="checkbox"
      :true-value="trueValue"
      :false-value="falseValue"
    >
    <div class="switch"></div>
  </div>
</template>

<script>
export default {
  model: {
    prop: 'checked',
    event: 'change'
  },
  props: {
    checked: [String, Number, Boolean, Function, Object, Array, Symbol],
    trueValue: {
      type: [String, Number, Boolean, Function, Object, Array, Symbol],
      default: true
    },
    falseValue: {
      type: [String, Number, Boolean, Function, Object, Array, Symbol],
      default: false
    }
  },
  data() {
    return {
      newValue: this.checked
    }
  },
  computed: {
    computedValue: {
      get() {
        return this.newValue
      },
      set(value) {
        this.newValue = value
        this.$emit('change', value)
      }
    }
  }
}
</script>

<style scoped lang="scss">
.base-switch {
  position: relative;
  display: inline-block;
  width: 50px;
  height: 30px;
}
input {
  position: absolute;
  top: 0;
  left: 0;
  margin: 0;
  z-index: 1;
  -webkit-appearance: none;
  outline: none;
  width: 100%;
  height: 100%;
  cursor: pointer;
}

input + .switch {
  position: relative;
  width: 50px;
  height: 30px;
  border-radius: 25px;
  background-color: #fff;
  transition: background-color .5s;
}
input + .switch:after {
  content: "";
  position: absolute;
  display: block;
  top: 2px;
  left: 2px;
  background: #fff;
  width: 26px;
  height: 26px;
  border-radius: 50%;
  box-shadow: 0 0 3px rgba(0,0,0,1);
  border: 1px solid #999;
  transition: right .5s, left .5s;
  box-sizing: border-box;
}

input:checked + .switch {
  background-color: #4ed164;
}

input:checked + .switch:after {
  left: 22px;
}
input:focus + .switch {
  box-shadow: 0 0 0 2px #06f;
}
</style>
