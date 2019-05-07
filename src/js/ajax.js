//alert("test");
// https://ky-yk-d.hatenablog.com/entry/2018/05/27/223135
// https://co.bsnws.net/article/234
// [フロントエンド] axiosライブラリを使って、柔軟にHTTP通信を行う
// https://www.yoheim.net/blog.php?q=20170801
// ファイル送信
// https://yk0807.com/techblog/2018/08/30/axios%E3%81%AE%E3%83%95%E3%82%A1%E3%82%A4%E3%83%AB%E9%80%81%E4%BF%A1%E3%81%A7%E3%83%8F%E3%83%9E%E3%81%A3%E3%81%9F%E8%A9%B1/

export default {
	created: function () {
	},
	methods: {
		getJson: function(url,collback) {
			console.log("GET実行");
			var $this = this;
			var storage = localStorage.getItem(url);
			var cacheJson = null;
			var loadFlg = true;
			// axiosでキャッシュされないようにする
			$this.$axios.interceptors.request.use(function (config) {
				if (typeof config.params === 'undefined') {
					config.params = {};
				}
				if (typeof config.params === 'object') {
					if (typeof URLSearchParams === 'function' && config.params instanceof URLSearchParams)
						config.params.append('_', Date.now());
					else
						config.params._ = Date.now();
				}

				return config;
			});

			//$this.$axios.get(url).then(function (response) {
			$this.$axios({
				method  : 'GET',
				url     : url,
				timeout : 1000,  // ms
//				headers: { 'Content-Type': 'application/json','Cookie': 'token=${token}' },
//				headers: { 'Content-Type': 'application/json','Cookie': 'token=test' },
				headers: {
					'Access-Control-Allow-Methods': 'GET,PUT,POST,DELETE,OPTIONS',
					'Content-Type': 'application/json'//,
					 //Cookie: "cookie1=value; cookie2=value; cookie3=value;"
					},
			 Cookie: "cookie1=value; cookie2=value; cookie3=value;",
				xsrfHeaderName: 'X-CSRF-Token',
				withCredentials: true
			}).then(function (response) {
				//console.log("ajax result");
				//console.log(response.data);
				localStorage.setItem(url,JSON.stringify(response.data));
				console.log("GET サーバからロード");
				collback(response.data);
			}).catch(function (response) {
				console.log("GET ストレージからロード");
				cacheJson = JSON.parse(storage);
				collback(cacheJson);
			});
		},
		postJson: function(url,collback) {
			console.log("POST実行");

			var $this = this;
			var storage = localStorage.getItem(url);
			var cacheJson = null;
			var loadFlg = true;
			// axiosでキャッシュされないようにする
			$this.$axios.interceptors.request.use(function (config) {
				if (typeof config.params === 'undefined') {
					config.params = {};
				}
				if (typeof config.params === 'object') {
					if (typeof URLSearchParams === 'function' && config.params instanceof URLSearchParams)
						config.params.append('_', Date.now());
					else
						config.params._ = Date.now();
				}

				return config;
			});

			//$this.$axios.get(url).then(function (response) {

			let params = new FormData();
			params.append("apikey","取得したAPIキー");
			params.append("query","おはよう" );
			//var params = { apikey: '取得したAPIキー', query: 'おはよう' };
console.log(params);
// Content-Type: application/x-www-form-urlencoded
			$this.$axios({
				method  : 'POST',
				url     : url,
				timeout : 2000,  // ms
				data    : params,
//				headers: { 'Content-Type': 'application/json','Cookie': 'token=${token}' },
				headers: {
					'Access-Control-Allow-Methods': 'GET,PUT,POST,DELETE,OPTIONS',
					'Content-Type': 'application/x-www-form-urlencoded'
//					'Content-Type': 'application/json',
//					 Cookie: "cookie1=value; cookie2=value; cookie3=value;"
					}//,
//				xsrfHeaderName: 'X-CSRF-Token',
//				withCredentials: true
			}).then(function (response) {
				//console.log("ajax result");
				//console.log(response.data);
				localStorage.setItem(url,JSON.stringify(response.data));
				console.log("POST サーバからロード");
				collback(response.data);
			}).catch(function (response) {
				console.log("POST ストレージからロード");
				cacheJson = JSON.parse(storage);
				collback(cacheJson);
			});
		}
	}
}
