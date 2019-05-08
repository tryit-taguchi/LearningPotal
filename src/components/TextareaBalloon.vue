<template>
  <div class="balloon">
    <textarea rows="1" :name="name" :id="id" :value="value"></textarea>
  </div>
</template>

<script>
export default {
  props: {
    name: String,
    id: String,
    value: String
  },
  mounted() {
    // autoheight
    const textarea = document.getElementsByTagName('textarea')[0];
    setTimeout(() => {
      textarea.style.cssText = 'height:auto;';
      textarea.style.cssText = 'height:' + textarea.scrollHeight + 'px';
    }, 0);
    textarea.addEventListener('keydown', () => {
      setTimeout(() => {
        textarea.style.cssText = 'height:auto;';
        textarea.style.cssText = 'height:' + textarea.scrollHeight + 'px';
      }, 0);
    });
  },
  data() {
    return { vModel: this.value }
  },
  // watch: {
  //   vModel(val) {
  //     this.$emit('input', val);
  //   }
  // }
}
</script>

<style lang="scss">
textarea {

}
.balloon{
  position: relative;
  margin: 0 100px 10px;
  padding-bottom: 40px;
  color: #333;
  &::before{
    content: "";
    display: block;
    border: 21px solid;
    border-color: #666 transparent transparent #666;
    position: absolute;
    bottom: 0;
    right: 100px;
  }
  &::after{
    content: "";
    display: block;
    border: 21px solid;
    border-color: #fff transparent transparent #fff;
    position: absolute;
    bottom: 3px;
    right: 99px;
  }
  %textarea{
    padding: 50px;
    vertical-align: middle;
    background-color: #fff;
    border-radius: 10px;
    font-size: 2.0rem;
    border: 1px solid #666;
    width: 100%;
    line-height: 1.2;
    font-family: "Nissan Brand font", "新ゴ";
    color: #000;
  }
  textarea{
    @extend %textarea;
    &:focus{
      outline: none;
    }
  }
  .textarea{
    @extend %textarea;
  }
}


</style>
