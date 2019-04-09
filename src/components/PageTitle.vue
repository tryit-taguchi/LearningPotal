<template>
  <h1 class="page-title">
    <span class="page-title-before" v-if="beforeTitle">{{beforeTitle}}</span>
    <span class="page-title-lt" v-if="hasSlotLeft"><slot name="left"></slot></span>
    <span class="page-title-rt"><slot></slot></span>
  </h1>
</template>

<script>
export default {
  props: {
    beforeTitle: String
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
  height: 80px;
  font-size: 3rem;
  margin: 20px 0 0;
}
.page-title-before {
  position: absolute;
  top: -2rem;
  font-size: 2rem;
  line-height: 1;
  background-color: rgba(0, 0, 0, 0);
  color: #656565;
}
.page-title-lt {
  flex: 0 1 304px;
  position: relative;
  color: #fff;
  background-color: #EB4D4B;
  display: flex;
  justify-content: center;
  align-items: center;
}
.page-title-lt-l {
  font-size: 1.4em;
}
.page-title-lt-xl {
  font-size: 2.0em;
}
.page-title-rt {
  flex: 1 0 720px;
  color: #fff;
  background-color: #EB4D4B;
  display: flex;
  align-items: center;
  line-height: 1;
  padding: 0 1em;
}
.page-title-lt + .page-title-rt {
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
