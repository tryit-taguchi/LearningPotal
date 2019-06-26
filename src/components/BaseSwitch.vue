<template>
  <div class="base-switch">
    <input
      type="checkbox"
      :checked="isChecked"
      @change="onChange"
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
    checked: {},
    trueValue: {
      default: true
    },
    falseValue: {
      default: false
    }
  },
  computed: {
    isChecked: function(){
      return this.checked === this.trueValue
    }
  },
  methods: {
    onChange: function(e){
      this.$emit('change', this.isChecked ? this.falseValue : this.trueValue)
    }
  }
}
</script>

<style scoped lang="scss">
$width: 50px;
$height: 30px;
$padding: 2px;
.base-switch {
  position: relative;
  display: inline-block;
  width: $width;
  height: $height;
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
  width: $width;
  height: $height;
  border-radius: calc( #{$height} / 2 );
  background-color: #fff;
  transition: background-color .5s;
}
input + .switch:after {
  content: "";
  position: absolute;
  display: block;
  top: $padding;
  left: $padding;
  background: #fff;
  width: calc( #{$height} - #{$padding} * 2 );
  height: calc( #{$height} - #{$padding} * 2 );
  border-radius: calc( #{$height} / 2 );
  box-shadow: 0 0 3px rgba(0,0,0,1);
  border: 1px solid #999;
  transition: left .5s;
  box-sizing: border-box;
}

input:checked + .switch {
  background-color: #4ed164;
}

input:checked + .switch:after {
  left: calc( #{$width} - #{$height} + #{$padding} );
}
input:focus + .switch {
  box-shadow: 0 0 0 2px #06f;
}
</style>
