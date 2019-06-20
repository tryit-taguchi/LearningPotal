import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

export default new Router({
	mode: 'history',
	base: process.env.BASE_URL,
	routes: [
		{
			path: '/',
			component: () => import('./views/AppInner.vue'),
			children: [
				// ホーム
				{
					path: '',
					name: 'home',
					component: () => import('./views/Home.vue'),
				},
				// ログイン画面（席番号入力）
				{
					path: 'login',
					name: 'login',
					component: () => import('./views/Login.vue'),
				},
				// 誓約書
				{
					path: 'agreement',
					name: 'agreement',
					component: () => import('./views/Agreement.vue'),
				},
				// オリエンテーション 質問（選択）
				{
					path: 'questions_1_q',
					name: 'questions_1_q',
					component: () => import('./views/Questions1Q.vue')
				},
				// オリエンテーション 回答（棒グラフ）
				{
					path: 'questions_1_a',
					name: 'questions_1_a',
					component: () => import('./views/Questions1A.vue')
				},
				// オリエンテーション 回答一覧（みんなの声にも流用）
				{
					path: 'questions_1_r',
					name: 'questions_1_r',
					component: () => import('./views/Questions1R.vue')
				},
				// 試乗① 質問（選択）
				{
					path: 'reporting_1_q',
					name: 'reporting_1_q',
					component: () => import('./views/Reporting1Q.vue')
				},
				// 試乗① 回答（レーダーチャート）
				{
					path: 'reporting_1_a',
					name: 'reporting_1_a',
					component: () => import('./views/Reporting1A.vue')
				},
				// 座学 質問（選択）
				{
					path: 'questions_2_q',
					name: 'questions_2_q',
					component: () => import('./views/Questions2Q.vue')
				},
				// 座学 回答（棒グラフ）
				{
					path: 'questions_2_a',
					name: 'questions_2_a',
					component: () => import('./views/Questions2A.vue')
				},
				// 座学 回答一覧（みんなの声にも流用）
				{
					path: 'questions_2_r',
					name: 'questions_2_r',
					component: () => import('./views/Questions2R.vue')
				},
				// 試乗② 質問（選択）
				{
					path: 'reporting_2_q',
					name: 'reporting_2_q',
					component: () => import('./views/Reporting2Q.vue')
				},
				// 試乗② 回答（レーダーチャート）
				{
					path: 'reporting_2_a',
					name: 'reporting_2_a',
					component: () => import('./views/Reporting2A.vue')
				},
				// 現車・競合車確認 質問（選択）
				{
					path: 'reporting_3_q',
					name: 'reporting_3_q',
					component: () => import('./views/Reporting3Q.vue')
				},
				// 現車・競合車確認 回答（レーダーチャート）
				{
					path: 'reporting_3_a',
					name: 'reporting_3_a',
					component: () => import('./views/Reporting3A.vue')
				},
				// まとめ① 質問（選択）
				{
					path: 'questions_3_q',
					name: 'questions_3_q',
					component: () => import('./views/Questions3Q.vue')
				},
				// まとめ① 回答（棒グラフ）
				{
					path: 'questions_3_a',
					name: 'questions_3_a',
					component: () => import('./views/Questions3A.vue')
				},
				// まとめ① 回答一覧（棒グラフ）（みんなの声用）
				{
					path: 'questions_3_r',
					name: 'questions_3_r',
					component: () => import('./views/Questions3R.vue')
				},
				// まとめ② 質問（複数選択）
				{
					path: 'questions_4_q',
					name: 'questions_4_q',
					component: () => import('./views/Questions4Q.vue')
				},
				// まとめ② 回答（棒グラフ）
				{
					path: 'questions_4_a',
					name: 'questions_4_a',
					component: () => import('./views/Questions4A.vue')
				},
				// まとめ② 回答一覧（棒グラフ）（みんなの声用）
				{
					path: 'questions_4_r',
					name: 'questions_4_r',
					component: () => import('./views/Questions4R.vue')
				},
				// 試乗系回答一覧（レーダーチャート）（みんなの声用）
				{
					path: 'reporting_all_r',
					name: 'reporting_all_r',
					component: () => import('./views/ReportingAllR.vue')
				},
				// キャッチフレーズ 入力
				{
					path: 'catchphrase_i',
					name: 'catchphrase_i',
					component: () => import('./views/CatchphraseI.vue')
				},
				// キャッチフレーズ 待ち
				{
					path: 'catchphrase_w',
					name: 'catchphrase_w',
					component: () => import('./views/CatchphraseW.vue')
				},
				// キャッチフレーズ 選出
				{
					path: 'catchphrase_s',
					name: 'catchphrase_s',
					component: () => import('./views/CatchphraseS.vue')
				},
				// キャッチフレーズ 結果（みんなの声にも流用）
				{
					path: 'catchphrase_r',
					name: 'catchphrase_r',
					component: () => import('./views/CatchphraseR.vue')
				},
				// 勉強会Q&A
				{
					path: 'faq',
					name: 'faq',
					component: () => import('./views/Faq.vue')
				},
				// 理解度確認テスト 質問（選択）
				{
					path: 'examinations_1_q',
					name: 'examinations_1_q',
					component: () => import('./views/Examinations1Q.vue')
				},
				// 理解度確認テスト 確認
				{
					path: 'examinations_1_c',
					name: 'examinations_1_c',
					component: () => import('./views/Examinations1C.vue')
				},
				// 理解度確認テスト 回答完了
				{
					path: 'examinations_1_f',
					name: 'examinations_1_f',
					component: () => import('./views/Examinations1F.vue')
				},
				// アンケート 質問（選択）
				{
					path: 'enquetes_1_q',
					name: 'enquetes_1_q',
					component: () => import('./views/Enquete1Q.vue')
				},
				// アンケート 確認
				{
					path: 'enquetes_1_c',
					name: 'enquetes_1_c',
					component: () => import('./views/Enquete1C.vue')
				},
				// アンケート 回答完了
				{
					path: 'enquetes_1_f',
					name: 'enquetes_1_f',
					component: () => import('./views/Enquete1F.vue')
				},
				// 設定
				{
					path: 'config',
					name: 'config',
					component: () => import('./views/Config.vue')
				},
				{
					path: 'barcharttest',
					component: () => import('./views/BarChartTest.vue')
				},
			]
		},
		// 印刷用
		{
			path: '/print/:lectureDate/:siteName/:lectureType',
			component: () => import('./views/Print.vue')
		},
		{
			path: '/*',
			component: () => import('./views/Error404.vue')
		}
	]
})
