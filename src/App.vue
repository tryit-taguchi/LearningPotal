<template>
  <div id="app">
    <app-header :user-company="userCompany" :user-name="userName" :img-logo="serverInfo.imgLogo" :img-title="serverInfo.imgTitle" :header-view-flg="headerViewFlg" ></app-header>
    <main>
      <transition name="fade" mode="out-in">
        <router-view :img-top-visual="serverInfo.imgTopVisual" />
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
			headerViewFlg : false,
		}
	},
	created: function () {
		console.log("------- App.vue");
		// -- ローディング時はじめの処理
		// サーバの共通情報を読む
		//console.log(this.getAPIPath()+'/serverInfo');
		console.log("サーバの共通情報読み込み");
		this.getJson(this.getAPIPath()+'/serverInfo',this.collback_ServerInfo);
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
			this.serverInfo.imgLogo = this.getUpfilesPath() + this.serverInfo.imgLogo;
      this.serverInfo.imgTitle = this.getUpfilesPath() + this.serverInfo.imgTitle;
      this.serverInfo.imgTopVisual = this.getUpfilesPath() + this.serverInfo.imgTopVisual;
			this.headerViewFlg = true;
			console.log("サーバの共通情報読み込み完了");
			console.log("アップファイルフォルダ : "+this.getUpfilesPath());
			//console.log(this.serverInfo);
		}
	}
}
</script>
