<template>
	<header class="page-header">
		<div class="inner">
			<router-link to="/">
				<!--<img class="logo" src="../assets/logo.png" alt="">-->
				<img class="logo" :src="imgLogo" alt="">
			</router-link>
			<div class="title">
				<!-- adminFlgが立っている場合は、タイトルタップでコンフィグモードに -->
				<router-link to="/config" v-if="adminFlg"><img :src="imgTitle" width="50%" alt="Learning Portal"></router-link>
				<!-- 通常はタイトルタップは無反応 -->
				<!-- <img v-else :src="imgTitle" width="50%" alt="Learning Portal"> -->
				<!-- （とりあえず今は反応状態にする） -->
				<router-link to="/config" v-else><img :src="imgTitle" width="50%" alt="Learning Portal"></router-link>
			</div>
			<span class="username" v-if="userName">ようこそ！ {{userCompany}} {{userName}}さん</span>
		</div>
	</header>
</template>

<script>
import { mapState } from "vuex";
export default {
	// データ定義
	data: function(){
		return {
		}
	},
	// 初回処理（createdではDOM操作をしない）
	created: async function () {
		console.log("ヘッダ処理開始");
		/*
		console.log(this.headerViewFlg);
		console.log("------- AppHeader.vue");
		console.log(this.userCompany);
		console.log(this.userName);
		*/
	},
	computed: {
		...mapState({
			userCompany: 'userCompany',
			userName: 'userName',
			adminFlg: 'adminFlg',
			imgLogo: state => state.serverInfo.imgLogo,
			imgTitle: state => state.serverInfo.imgTitle
		})
	}
}
</script>

<style scoped lang="scss">
.page-header {
	position: fixed;
	top: 0;
	width: 100%;
	height: 120px;
	background-color: #000;
	z-index: 100;
	.inner {
		max-width: 1024px;
		height: 100%;
		margin: 0 auto;
		position: relative;
	}
	.logo {
		position: absolute;
		left: 20px;
		height: 120px;
		width: auto;
		cursor: pointer;

		// display: block;
		// width: 100px;
		// height: 120px;
		// box-shadow: inset 0 0 0 1px #fff;
		// color: #fff;
	}
	.title {
		margin: 0 0 0 140px;
		font-size: 4.8rem;
		line-height: 1.8;
		border-bottom: 5px solid #c3002f;
		img {
			transform: translate(0px, 10px);
		}
		span {
			color: #808080;
		}
		a {
			text-decoration: none;
		}
	}
	.username {
		position: absolute;
		bottom: 0;
		left: 0;
		margin: 0 0 0 140px;
		font-size: 1.8rem;
	}
}
</style>
