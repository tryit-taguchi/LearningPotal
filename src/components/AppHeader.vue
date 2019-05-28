<template>
  <header class="page-header">
    <div class="inner" v-if="headerViewFlg">
      <router-link to="/">
        <!--<img class="logo" src="../assets/logo.png" alt="">-->
        <img class="logo" :src="imgLogo" alt="">
      </router-link>
      <!-- adminFlgが立っている場合は、タイトルタップでコンフィグモードに -->
      <div class="title" v-if="adminFlg">
        <!-- <img src="../assets/title.svg" width="640" alt="CMC GROUP Learning Portal"> -->
        <router-link to="/config">
          <img :src="imgTitle" width="50%" alt="Learning Portal">
        </router-link>
        <!--Learning Portal-->
      </div>
      <!-- 通常はタイトルタップは無反応 -->
      <div class="title" v-else>
        <!-- <img src="../assets/title.svg" width="640" alt="CMC GROUP Learning Portal"> -->
        <img :src="imgTitle" width="50%" alt="Learning Portal">
        <!--Learning Portal-->
      </div>
      <span class="username" v-if="userName != ''">ようこそ！ {{userCompany}} {{userName}}さん</span>
    </div>
  </header>
</template>

<script>
export default {
	props: {
		userName: String,
		userCompany: String,
		imgLogo: String,
		imgTitle: String,
		headerViewFlg: Boolean,
		adminFlg: Boolean,
	},
	// データ定義
	data: function(){
		return {
			pageViewFlg: false, // データセット後に描画を行う
		}
	},
	// 初回処理（createdではDOM操作をしない）
	created: async function () {
		console.log("ヘッダ処理開始");
//		this.adminFlg = "";
		/*
		console.log(this.headerViewFlg);
		console.log("------- AppHeader.vue");
		console.log(this.userCompany);
		console.log(this.userName);
		*/
	},
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
