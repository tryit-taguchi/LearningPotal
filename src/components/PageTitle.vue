<template>
  <h1 class="page-title">
    <span class="before" v-if="beforeText">{{beforeText}}</span>
    <span class="left" v-if="hasSlotLeft"><slot name="left"></slot></span>
    <span class="right"><slot></slot></span>
  </h1>
</template>

<script>
export default {
  props: {
    beforeText: String
  },
  computed: {
    hasSlotLeft() {
      return !!this.$slots.left
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
.page-title {
  position: relative;
  display: flex;
  height: 100px;
  font-size: 3rem;
  margin: 0;
  padding: 2rem 0 0;
}
.before {
  position: absolute;
  top: 0;
  font-size: 2rem;
  line-height: 1;
  background-color: rgba(0, 0, 0, 0);
  color: #656565;
}
.left {
  flex: 0 1 304px;
  position: relative;
  color: #fff;
  background-color: #EB4D4B;
  display: flex;
  justify-content: center;
  align-items: center;
}
.right {
  flex: 1 0 720px;
  color: #fff;
  background-color: #EB4D4B;
  display: flex;
  align-items: center;
  line-height: 1;
  padding: 0 1em;
}
.left + .right {
  color: #333;
  background-color: #F7F7F7;
}
.page-title.sticky {
  position: sticky;
  top: 160px;
}
.page-title.sticky::before {
  content: '';
  display: block;
  width: 100%;
  height: 40px;
  background-color: #fff;
  position: absolute;
  top: -40px;
}
</style>
