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
	},
	methods: {
		// ログインチェック
		isLogin : function() {
			var seatCd    = this.$cookies.set('SEAT_CD');
			var lectureDt = this.$cookies.set('LECTURE_DT');
			var nowYMD    = this.dateToFormatString(new Date(), '%YYYY%-%MM%-%DD%');
			var toLogin   = false;
			console.log("------- Home.vue");
			console.log("seatCd:" + seatCd);
			console.log("lectureDt:" + lectureDt);
			console.log("nowYMD:" + nowYMD);

			if( seatCd == null || seatCd == "" ) {
				toLogin = true;
			} else {
				if( seatCd.slice( 0, 1) != "#" ) {
					// 講師モードでなければ、日付も確認する
					if( nowYMD != lectureDt ) {
						toLogin = true;
					}
				}
			}

			// ログインすべきだったらログイン画面に遷移
			if( toLogin == true ) {
				this.toLogout();
				this.$router.push({ name: 'login' });
			} else {
				this.$parent.userCompany = this.$cookies.get('COMPANY_NAME');
				this.$parent.userName    = this.$cookies.get('MEMBER_NAME');
			}
		},
		// ログイン情報をクッキーに保存
		toLogin : function(login) {
			this.$cookies.set('MEMBER_ID',login.MEMBER_ID);
			this.$cookies.set('SEAT_CD',login.SEAT_CD);
			this.$cookies.set('GROUP_CD',login.GROUP_CD);
			this.$cookies.set('MEMBER_NAME',login.MEMBER_NAME);
			this.$cookies.set('COMPANY_NAME',login.COMPANY_NAME);
			this.$cookies.set('LECTURE_DT',login.LECTURE_DT);
			this.$parent.userCompany = login.COMPANY_NAME;
			this.$parent.userName    = login.MEMBER_NAME;
		},
		// ログイン情報をクッキーに保存
		toLogout : function() {
			this.$cookies.set('MEMBER_ID','');
			this.$cookies.set('SEAT_CD','');
			this.$cookies.set('GROUP_CD','');
			this.$cookies.set('MEMBER_NAME','');
			this.$cookies.set('COMPANY_NAME','');
			this.$cookies.set('LECTURE_DT','');
			this.$parent.userCompany = '';
			this.$parent.userName    = '';
		},
		// ユーザーID取得
		getMemberId : function() {
			return this.$cookies.get('MEMBER_ID');
		},

		dateToFormatString : function(date, fmt, locale, pad) {
			// %fmt% を日付時刻表記に。
			// 引数
			//  date:  Dateオブジェクト
			//  fmt:   フォーマット文字列、%YYYY%年%MM%月%DD%日、など。
			//  locale:地域指定。デフォルト（入力なし）の場合はja-JP（日本）。現在他に対応しているのはen-US（英語）のみ。
			//  pad:   パディング（桁数を埋める）文字列。デフォルト（入力なし）の場合は0。
			// 例：2016年03月02日15時24分09秒
			// %YYYY%:4桁年（2016）
			// %YY%:2桁年（16）
			// %MMMM%:月の長い表記、日本語では数字のみ、英語ではMarchなど（3）
			// %MMM%:月の短い表記、日本語では数字のみ、英語ではMar.など（3）
			// %MM%:2桁月（03）
			// %M%:月（3）
			// %DD%:2桁日（02）
			// %D%:日（2）
			// %HH%:2桁で表した24時間表記の時（15）
			// %H%:24時間表記の時（15）
			// %h%:2桁で表した12時間表記の時（03）
			// %h%:12時間表記の時（3）
			// %A%:AM/PM表記（PM）
			// %A%:午前/午後表記（午後）
			// %mm%:2桁分（24）
			// %m%:分（24）
			// %ss%:2桁秒（09）
			// %s%:秒（9）
			// %W%:曜日の長い表記（水曜日）
			// %w%:曜日の短い表記（水）
			var padding = function(n, d, p) {
				p = p || '0';
				return (p.repeat(d) + n).slice(-d);
			};
			var DEFAULT_LOCALE = 'ja-JP';
			var getDataByLocale = function(locale, obj, param) {
				var array = obj[locale] || obj[DEFAULT_LOCALE];
				return array[param];
			};
			var format = {
				'YYYY': function() { return padding(date.getFullYear(), 4, pad); },
				'YY': function() { return padding(date.getFullYear() % 100, 2, pad); },
				'MMMM': function(locale) {
					var month = {
						'ja-JP': ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'],
						'en-US': ['January', 'February', 'March', 'April', 'May', 'June',
								  'July', 'August', 'September', 'October', 'November', 'December'],
					};
					return getDataByLocale(locale, month, date.getMonth());
				},
				'MMM': function(locale) {
					var month = {
						'ja-JP': ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'],
						'en-US': ['Jan.', 'Feb.', 'Mar.', 'Apr.', 'May', 'June',
								  'July', 'Aug.', 'Sept.', 'Oct.', 'Nov.', 'Dec.'],
					};
					return getDataByLocale(locale, month, date.getMonth());
				},
				'MM': function() { return padding(date.getMonth()+1, 2, pad); },
				'M': function() { return date.getMonth()+1; },
				'DD': function() { return padding(date.getDate(), 2, pad); },
				'D': function() { return date.getDate(); },
				'HH': function() { return padding(date.getHours(), 2, pad); },
				'H': function() { return date.getHours(); },
				'hh': function() { return padding(date.getHours() % 12, 2, pad); },
				'h': function() { return date.getHours() % 12; },
				'mm': function() { return padding(date.getMinutes(), 2, pad); },
				'm': function() { return date.getMinutes(); },
				'ss': function() { return padding(date.getSeconds(), 2, pad); },
				's': function() { return date.getSeconds(); },
				'A': function() {
					return date.getHours() < 12 ? 'AM' : 'PM';
				},
				'a': function(locale) {
					var ampm = {
						'ja-JP': ['午前', '午後'],
						'en-US': ['am', 'pm'],
					};
					return getDataByLocale(locale, ampm, date.getHours() < 12 ? 0 : 1);
				},
				'W': function(locale) {
					var weekday = {
						'ja-JP': ['日曜日', '月曜日', '火曜日', '水曜日', '木曜日', '金曜日', '土曜日'],
						'en-US': ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
					};
					return getDataByLocale(locale, weekday, date.getDay());
				},
				'w': function(locale) {
					var weekday = {
						'ja-JP': ['日', '月', '火', '水', '木', '金', '土'],
						'en-US':  ['Sun', 'Mon', 'Tue', 'Wed', 'Thur', 'Fri', 'Sat'],
					};
					return getDataByLocale(locale, weekday, date.getDay());
				},
			};
			var fmtstr = ['']; // %%（%として出力される）用に空文字をセット。
			Object.keys(format).forEach(function(key) {
				fmtstr.push(key); // ['', 'YYYY', 'YY', 'MMMM',... 'W', 'w']のような配列が生成される。
			})
			var re = new RegExp('%(' + fmtstr.join('|') + ')%', 'g');
			// /%(|YYYY|YY|MMMM|...W|w)%/g のような正規表現が生成される。
			var replaceFn = function(match, fmt) {
			// match には%YYYY%などのマッチした文字列が、fmtにはYYYYなどの%を除くフォーマット文字列が入る。
				if(fmt === '') {
					return '%';
				}
				var func = format[fmt];
				// fmtがYYYYなら、format['YYYY']がfuncに代入される。つまり、
				// function() { return padding(date.getFullYear(), 4, pad); }という関数が代入される。
				if(func === undefined) {
					//存在しないフォーマット
					return match;
				}
				return func(locale);
			};
			return fmt.replace(re, replaceFn);
		}
	},
	mounted: function () {
	}
}


