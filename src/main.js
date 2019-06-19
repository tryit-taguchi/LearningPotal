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

// charts
import VueApexCharts from 'vue-apexcharts'
Vue.use(VueApexCharts)
Vue.component('apexchart', VueApexCharts)

require('@/css/style.scss')

Vue.config.productionTip = false

// modal
import VModal from 'vue-js-modal'
Vue.use(VModal, { dialog: true, dynamic: true })

// smooth scroll
var VueScrollTo = require('vue-scrollto');
Vue.use(VueScrollTo, {
  duration: 300,
  easing: "ease-in-out",
  offset: -120,
})

// anime.js
import anime from 'animejs'
Vue.use((Vue, options) => {
  Vue.prototype.$anime = anime
});

// service-worker.js 組み込み
if (process.env.NODE_ENV === 'production' && 'serviceWorker' in navigator) {
  navigator.serviceWorker.register('/service-worker.js');
}

new Vue({
	router,
	store,
	render: h => h(App),
	data: function(){
		return {}
	},
	created: function(){
	},
	methods: {
	}
}).$mount('#app')
