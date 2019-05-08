//alert("test");
// https://ky-yk-d.hatenablog.com/entry/2018/05/27/223135
// https://co.bsnws.net/article/234
// [フロントエンド] axiosライブラリを使って、柔軟にHTTP通信を行う
// https://www.yoheim.net/blog.php?q=20170801
// ファイル送信
// https://yk0807.com/techblog/2018/08/30/axios%E3%81%AE%E3%83%95%E3%82%A1%E3%82%A4%E3%83%AB%E9%80%81%E4%BF%A1%E3%81%A7%E3%83%8F%E3%83%9E%E3%81%A3%E3%81%9F%E8%A9%B1/
//var serverInfo;
export default {
	created: function () {
console.log(this.serverInfo);
//		console.log(process.env.VUE_APP_API_URL_BASE+'/serverInfo');
//		this.getJson(process.env.VUE_APP_API_URL_BASE+'/serverInfo',this.collback_ServerInfo);
	},
	methods: {
//	    collback_ServerInfo: function(response) {
//			serverInfo = response.data;
//			console.log(response.data);
//	    }
	},
	mounted: function () {
	}
}
