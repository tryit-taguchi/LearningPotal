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
Vue.mixin(ajax)

//import { Mixin } from './js/common.js';
//require('./js/common.js');

//var ajax         = new Object;

Vue.use(VueAxios, axios);
Vue.prototype.$axios = axios;
//Vue.use(Cookies, Cookies);
Vue.prototype.$cookies = Cookies;

//console.log("API_BASE_URL");
//console.log(this.$dotenv.process.env.API_BASE_URL);
//console.log(process.env.API_BASE_URL);
//console.log(process.env);

//Vue.prototype.$ajax  = ajax;
/*
var ajax         = new Object;

ajax.getJson = function(url,$obj) {
	fetch(url).then(function (response) {
		return response.json();
	}).then(function (json) {
		$obj.questions = json;
	});
}
*/

// コンポーネントを全て自動でグローバルに登録
const files = require.context('./components', true, /\.vue$/);
const components = {};
files.keys().forEach(key => {
  components[key.replace(/(\.\/|\.vue)/g, '')] = files(key).default;
});
Object.keys(components).forEach(key => {
  console.log(key);
  Vue.component(key, components[key]);
});

require('@/css/style.scss')

Vue.config.productionTip = false

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
