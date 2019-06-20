<template>
	<transition>
		<router-view v-if="appViewFlg" />
	</transition>
</template>

<style scoped lang="scss">

</style>

<script>
export default {
	data: function(){
		return {
			appViewFlg : false,
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
		console.log("App.vue 終了");
	},
	methods: {
		collback_ServerInfo: async function(response) {
			// this.serverInfo 変数に共通サーバ情報を収納
			// this.serverInfo = response.data;
			const serverInfo = response.data;
			serverInfo.imgLogo = this.getUpfilesPath() + serverInfo.imgLogo;
			serverInfo.imgTitle = this.getUpfilesPath() + serverInfo.imgTitle;
			serverInfo.imgTopVisual = this.getUpfilesPath() + serverInfo.imgTopVisual;
			this.$store.commit('setServerInfo', serverInfo)
			this.appViewFlg = true;
			console.log("サーバの共通情報読み込み完了");
			//console.log("アップファイルフォルダ : "+this.getUpfilesPath());
			//console.log(this.serverInfo);
		}
	}
}
</script>
