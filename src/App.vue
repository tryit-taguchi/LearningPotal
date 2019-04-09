<template>
  <div id="app">
    <app-header user-company="XXX会社" user-name="○○○"></app-header>
    <main>
      <transition name="fade" mode="out-in">
        {{$store.getters.isLoggedIn}}
        <router-view/>
        }
      </transition>
    </main>
  </div>
</template>

<style lang="scss">
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  color: #2c3e50;
}
main{
  width: 100%;
  max-width: 1024px;
  margin: 160px auto 0;
  position: relative;
}
.fade-enter-active, .fade-leave-active {
  transition: opacity .5s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0;
}
</style>

<script>
export default {
  data: function(){
    return {
    }
  }
}
</script>
