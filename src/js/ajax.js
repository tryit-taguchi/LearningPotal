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
		getJson: async function(url,collback,collback2) {
			console.log("HTTPメソッド「GET」実行 : " + url);
			var $this = this;
			var cacheJson = null;
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
			return await $this.$axios({
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
			}).then(await function (response) {
				console.log("GET サーバからロード : " + url);
				if( collback.name != "" ) {
					console.log("関数名 : " + collback.name);
				} else {
					console.log("関数名 : 無名関数");
				}
				if( collback != null ) {
					localStorage.setItem(url,JSON.stringify(response.data));
					collback(response.data);
				}
			}).catch(function (response) {
				console.log("キャッチされた？");
/*
				console.log("GET ストレージからロード : " + url);
				var storage = localStorage.getItem(url);
				cacheJson = JSON.parse(storage);
				if( collback != null ) {
					collback(cacheJson);
				}
*/
			});
		},
		postJson: async function(url,params,collback) {
			console.log("HTTPメソッド「POST」実行");

			var $this = this;
			//var storage = localStorage.getItem(url);
			//var cacheJson = null;
			// axiosでキャッシュされないようにする
			await $this.$axios.interceptors.request.use(function (config) {
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

			$this.$axios({
				method  : 'POST',
				url     : url,
				timeout : 3000,  // ms
				data    : params,
				headers: {
					'Access-Control-Allow-Methods': 'GET,PUT,POST,DELETE,OPTIONS',
					'Content-Type': 'application/x-www-form-urlencoded'
					}
			}).then(function (response) {
				localStorage.setItem(url,JSON.stringify(response.data));
				console.log("POST サーバからロード");
				if( collback != null ) {
					collback(response.data);
				}
			}).catch(function (response) {
				alert("通信ができません。");
				/*
				console.log("POST ストレージからロード");
				cacheJson = JSON.parse(storage);
				collback(cacheJson);
				*/
			});
		},
		deleteJson: function(url,collback) {
			console.log("HTTPメソッド「DELETE」実行");

			var $this = this;
			//var storage = localStorage.getItem(url);
			//var cacheJson = null;
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

			$this.$axios({
				method  : 'DELETE',
				url     : url,
				timeout : 3000,  // ms
				headers: {
					'Access-Control-Allow-Methods': 'GET,PUT,POST,DELETE,OPTIONS',
					'Content-Type': 'application/x-www-form-urlencoded'
					}
			}).then(function (response) {
				localStorage.setItem(url,JSON.stringify(response.data));
				console.log("POST サーバからロード");
				if( collback != null ) {
					collback(response.data);
				}
			}).catch(function (response) {
				alert("通信ができません。");
				/*
				console.log("POST ストレージからロード");
				cacheJson = JSON.parse(storage);
				collback(cacheJson);
				*/
			});
		}
	}
}
