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
  margin: 130px auto 0;
  position: relative;
}
</style>

<script>
export default {
	data: function(){
		return {
			userCompany: '',
			userName: '',
		}
	},
	mounted: function () {
		/*
		console.log("------- App.vue");
		console.log(this.userCompany);
		console.log(this.userName);
		*/
	}
}
</script>
