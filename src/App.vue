<template>
  <div id="app">
    <app-header :user-company="userCompany" :user-name="userName"></app-header>
    <main>
      <transition name="fade" mode="out-in">
        {{$store.getters.isLoggedIn}}
        <router-view/>
      </transition>
    </main>
  </div>
</template>

<style lang="scss">
main{
  width: 100%;
  max-width: 1024px;
  margin: 160px auto 0;
  position: relative;
}
</style>

<script>
export default {
  data: function(){
    return {
      userCompany: 'XXX会社',
      userName: '○○○'
    }
  }
}
</script>
