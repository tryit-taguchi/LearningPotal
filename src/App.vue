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
			session: '',
			serverInfo : {},
		}
	},
	created: function () {
		console.log("------- App.vue");
		// -- ローディング時はじめの処理
		// サーバの共通情報を読む
		//console.log(process.env.VUE_APP_API_URL_BASE+'/serverInfo');
		console.log("サーバの共通情報読み込み");
		this.getJson(process.env.VUE_APP_API_URL_BASE+'/serverInfo',this.collback_ServerInfo);
		/*
		console.log("------- App.vue");
		console.log(this.userCompany);
		console.log(this.userName);
		*/
	},
	methods: {
		collback_ServerInfo: async function(response) {
			// this.serverInfo 変数に共通サーバ情報を収納
			this.serverInfo = response.data;
			console.log("サーバの共通情報読み込み完了");
			console.log("アップファイルフォルダ : "+process.env.VUE_APP_UPFILES_URL_BASE);
			console.log(this.serverInfo);
		}
	}
}
</script>
