/*
npm install js-cookie --save
npm install dotenv --save
*/
import Vue from 'vue'
import axios from 'axios'
import VueAxios from 'vue-axios'
import App from './App.vue'
import router from './router'
import store from './store'
import Cookies from 'js-cookie';
import './registerServiceWorker'

import ajax from './js/ajax';
Vue.mixin(ajax);
import common from './js/common';
Vue.mixin(common);
//var serverInfo;
//Vue.mixin(serverInfo);

//import { Mixin } from './js/common.js';
//require('./js/common.js');

Vue.use(VueAxios, axios);
Vue.use(router);
Vue.prototype.$axios = axios;
Vue.prototype.$cookies = Cookies;
//Vue.prototype.$router = router;

// コンポーネントを全て自動でグローバルに登録
const files = require.context('./components', true, /\.vue$/);
const components = {};
files.keys().forEach(key => {
  components[key.replace(/(\.\/|\.vue)/g, '')] = files(key).default;
});
Object.keys(components).forEach(key => {
  //console.log(key);
  Vue.component(key, components[key]);
});

require('@/css/style.scss')

Vue.config.productionTip = false

new Vue({
	router,
	store,
	render: h => h(App),
	created: function(){
		// -- ローディング時はじめの処理
		// サーバの共通情報を読む
		//console.log(process.env.VUE_APP_API_URL_BASE+'/serverInfo');
		console.log("サーバの共通情報読み込み");
		this.getJson(process.env.VUE_APP_API_URL_BASE+'/serverInfo',this.collback_ServerInfo);
	},
	methods: {
	    collback_ServerInfo: function(response) {
			// this.serverInfo 変数に共通サーバ情報を収納
			this.serverInfo = response.data;
			//console.log(response.data);
	    }
	}
}).$mount('#app')
