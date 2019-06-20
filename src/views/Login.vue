<template>
		<div  v-if="pageViewFlg" style="text-align:center">
			<page-title>
				ログイン（席番号入力）
			</page-title>
			<input type="text" v-model="SEAT_CD_1" placeholder="" maxlength="1" style="font-size:70pt;width:90pt;text-align:center; padding:0;">
			<span style="font-size:70pt">－</span>
			<input type="text" v-model="SEAT_CD_2" placeholder="" maxlength="1" style="font-size:70pt;width:90pt;text-align:center; padding:0;" readonly="readonly">
			<input type="text" v-model="SEAT_CD_3" placeholder="" maxlength="1" style="font-size:70pt;width:90pt;text-align:center; padding:0;" readonly="readonly">
			<br>
			<table align="center">
				<tr>
					<td>
						<table class="softkey">
							<tr>
								<td @click="softkeyA($event,'A')">A</td>
								<td @click="softkeyA($event,'B')">B</td>
								<td @click="softkeyA($event,'C')">C</td>
								<td @click="softkeyA($event,'D')">D</td>
								<td @click="softkeyA($event,'E')">E</td>
								<td @click="softkeyA($event,'F')">F</td>
								<td @click="softkeyA($event,'G')">G</td>
							</tr>
							<tr>
								<td @click="softkeyA($event,'H')">H</td>
								<td @click="softkeyA($event,'I')">I</td>
								<td @click="softkeyA($event,'J')">J</td>
								<td @click="softkeyA($event,'K')">K</td>
								<td @click="softkeyA($event,'L')">L</td>
								<td @click="softkeyA($event,'M')">M</td>
								<td @click="softkeyA($event,'N')">N</td>
							</tr>
							<tr>
								<td @click="softkeyA($event,'O')">O</td>
								<td @click="softkeyA($event,'P')">P</td>
								<td @click="softkeyA($event,'Q')">Q</td>
								<td @click="softkeyA($event,'R')">R</td>
								<td @click="softkeyA($event,'S')">S</td>
								<td @click="softkeyA($event,'T')">T</td>
								<td @click="softkeyA($event,'U')">U</td>
							</tr>
							<tr>
								<td @click="softkeyA($event,'V')">V</td>
								<td @click="softkeyA($event,'W')">W</td>
								<td @click="softkeyA($event,'X')">X</td>
								<td @click="softkeyA($event,'Y')">Y</td>
								<td @click="softkeyA($event,'Z')">Z</td>
								<td></td>
								<td></td>
							</tr>
						</table>
					</td>
					<td style="width:30px">
					</td>
					<td>
						<table class="softkey">
							<tr>
								<td @click="softkeyN($event,'7')">7</td>
								<td @click="softkeyN($event,'8')">8</td>
								<td @click="softkeyN($event,'9')">9</td>
							</tr>
							<tr>
								<td @click="softkeyN($event,'4')">4</td>
								<td @click="softkeyN($event,'5')">5</td>
								<td @click="softkeyN($event,'6')">6</td>
							</tr>
							<tr>
								<td @click="softkeyN($event,'1')">1</td>
								<td @click="softkeyN($event,'2')">2</td>
								<td @click="softkeyN($event,'3')">3</td>
							</tr>
							<tr>
								<td @click="softkeyN($event,'0')">0</td>
								<td colspan="2" @click="softkeyC($event)">Clear</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<base-button text="ログイン" @click="save" />
			<p>{{errorMessage}}</p>
		</div>
</template>

<style lang="scss">
table.softkey {
	border-collapse:collapse;
	border:0px;
}
table.softkey tr td {
	padding:2px;
	font-size:24pt;
	width: 60pt;
	border:1px solid #fff;
}
</style>

<script>
export default {
	// データ定義
	data: function(){
		return {
			errorMessage: '',
			SEAT_CD: process.env.VUE_APP_DEFAULT_SEAT_CD,
			SEAT_CD_1: '',
			SEAT_CD_2: '',
			SEAT_CD_3: '',
			oldColor: '#000',
			blinkColor: '#88c',
			pageViewFlg: false, // データセット後に描画を行う
		}
	},
	// 初回処理
	created: function () {
		this.SEAT_CD_1 = this.SEAT_CD.charAt(0);
		this.SEAT_CD_2 = this.SEAT_CD.charAt(1);
		this.SEAT_CD_3 = this.SEAT_CD.charAt(2);
		console.log("ログアウト処理");
		var loginFlg = true;
		var login = JSON.parse(localStorage.getItem('login')); // ストレージに保存してあったログイン情報を確認する
		var nowYMD    = this.dateToFormatString(new Date(), '%YYYY%-%MM%-%DD%');
		if( !this.isEmpty(login) ) {
			if( !this.isEmpty(login.SEAT_CD) ) {
				if( login.LECTURE_DT == '0000-00-00'
				 || login.LECTURE_DT == nowYMD ) {
					console.log("再ログイン処理");
					loginFlg = false;
					this.SEAT_CD = login.SEAT_CD;
					let params = new FormData();
					// 再ログイン処理
					params.append("SEAT_CD",this.SEAT_CD);
					this.postJson(this.getAPIPath()+'/login',params, function (response) {
						if( response.data != null ) {
							this.login = response.data.member;
							this.$store.commit('setSession', response.data.session)
						}
						if( this.login != null ) {
							// ログイン情報をクッキーに保存
							this.toLogin(this.login);
							if( !this.isEmpty(localStorage.getItem('lastestUrl')) ) {
								this.jump(localStorage.getItem('lastestUrl')); // 自動ログインできたら最後にアクセスしたURL
							} else {
								this.jump('/'); // 最後にアクセスしたURLが無かったらHomeに遷移
							}
						}
					}.bind(this));
				}
			}
		}
		if( loginFlg == true ) {
			this.toLogout();
			this.pageViewFlg = true;
		}
	},
	// メソッド群
	methods: {
		// バリデーション
		validation: function () {
			this.SEAT_CD = this.SEAT_CD_1+this.SEAT_CD_2+this.SEAT_CD_3;

			if( this.SEAT_CD_1 == ''
			 || this.SEAT_CD_2 == ''
			 || this.SEAT_CD_3 == '' ) {
				this.errorMessage = "座席番号を入力してください。";
				return false;
			}
			return true;
		},
		// softkey アルファベット
		softkeyA: function (event,val) {
			console.log(val);
			this.SEAT_CD_1 = val;
			this.softkeyClickColor(event); // 色を変える
		},
		/*
		// softkey アルファベット
		softkeyA2: function (event,val) {
			return function(event,val) {
				console.log(val);
				this.SEAT_CD_1 = val;
				this.softkeyClickColor(event); // 色を変える
			}
		},
		*/

		// softkey 数字
		softkeyN: function (event,val) {
			console.log(val);
			if( this.SEAT_CD_2 == '' ) {
				this.SEAT_CD_2 = val;
			} else if( this.SEAT_CD_3 == '' ) {
				this.SEAT_CD_3 = val;
			} else {
				this.SEAT_CD_3 = val;
			}
			this.softkeyClickColor(event); // 色を変える
		},
		// softkey クリア
		softkeyC: function (event) {
			console.log("Clear");
			this.SEAT_CD_1 = '';
			this.SEAT_CD_2 = '';
			this.SEAT_CD_3 = '';
			this.softkeyClickColor(event); // 色を変える
		},
		// softkey クリックカラー処理
		softkeyClickColor: function (event) {
			event.target.style.background = this.blinkColor;
			setTimeout(() => {
				event.target.style.background = this.oldColor;
			}, 200);
		},

		// 保存
		save: function () {
			if( this.validation() ) {
				let params = new FormData();
				params.append("SEAT_CD",this.SEAT_CD);
				this.postJson(this.getAPIPath()+'/login',params,this.collback);
			}
		},
		// サーバサイドからのコールバック
		collback: function(response) {
			if( response.data != null ) {
				this.login = response.data.member;
				this.$store.commit('setSession', response.data.session)
			}
			if( this.login != null ) {
				// ログイン情報をクッキーに保存
				this.toLogin(this.login);
				//this.$router.push({ name: 'agreement' }); // ログインできたら誓約書画面に遷移
				this.jump({ name: 'agreement' }); // ログインできたら誓約書画面に遷移
				//console.log("登録されている受講者です");
			} else {
				// 違ってたらログアウトさせる
				this.toLogout();
				this.errorMessage = "登録されていない受講者です。";
			}
		},
	}
}
</script>
