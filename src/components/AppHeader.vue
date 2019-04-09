<template>
  <header class="page-header">
    <div class="page-header-inner">
      <router-link to="/"><img class="logo" src="../assets/logo.svg" alt=""></router-link>
      <div class="title">
        <img src="../assets/title.svg" width="640" alt="CMC GROUP Learning Portal">
      </div>
      <span class="username">ようこそ！ {{userCompany}} {{userName}}さん</span>
    </div>
  </header>
</template>

<script>
export default {
  props: {
    userName: String,
    userCompany: String
  }
}
</script>

<style scoped lang="scss">
.page-header {
  position: fixed;
  top: 0;
  width: 100%;
  height: 120px;
  background-color: #fff;
  z-index: 100;
}
.page-header-inner {
  max-width: 1024px;
  height: 100%;
  margin: 0 auto;
  position: relative;
}
.page-header .logo {
  position: absolute;
  left: 20px;
  height: 120px;
  width: auto;
  cursor: pointer;
}
.page-header .title {
  color: #333;
  margin: 0 0 0 140px;
  font-size: 4.8rem;
  line-height: 1.8;
  border-bottom: 5px solid #808080;
}
.page-header .title img {
  -webkit-transform: translate(0px, 10px);
          transform: translate(0px, 10px);
}
.page-header .title span {
  color: #808080;
}
.page-header .title a {
  text-decoration: none;
}
.page-header .username {
  position: absolute;
  bottom: 0;
  left: 0;
  margin: 0 0 0 140px;
  font-size: 1.8rem;
}
</style>
