
・基本プリフィックス
login             => ログイン画面
agreement         => 誓約書
questions_1       => オリエンテーション
questions_2       => 座学
questions_3       => まとめ①
questions_4       => まとめ②
reporting_1       => 試乗①
reporting_2       => 試乗②
reporting_3       => 現車・競合車確認
examinations_1    => 理解度確認テスト
enquetes_1        => アンケート
catchphrase       => キャッチフレーズ
faq               => 勉強会Q&A

・ルーティング
/                 => ホーム
/login            => ログイン画面（席番号入力）
/agreement        => 誓約書
/questions_1_q    => オリエンテーション 質問（選択）
/questions_1_a    => オリエンテーション 回答（棒グラフ）
/questions_1_r    => オリエンテーション 回答一覧（みんなの声にも流用）
/reporting_1_q    => 試乗① 質問（選択）
/reporting_1_a    => 試乗① 回答（レーダーチャート）
/questions_2_q    => 座学 質問（選択）
/questions_2_a    => 座学 回答（棒グラフ）
/questions_2_r    => 座学 回答一覧（みんなの声にも流用）
/reporting_2_q    => 試乗② 質問（選択）
/reporting_2_a    => 試乗② 回答（レーダーチャート）
/reporting_3_q    => 現車・競合車確認 質問（選択）
/reporting_3_a    => 現車・競合車確認 回答（レーダーチャート）
/questions_3_q    => まとめ① 質問（選択）
/questions_3_a    => まとめ① 回答（棒グラフ）
/questions_3_r    => まとめ① 回答一覧（棒グラフ）（みんなの声用）
/questions_4_q    => まとめ② 質問（複数選択）
/questions_4_a    => まとめ② 回答（棒グラフ）
/questions_4_r    => まとめ② 回答一覧（棒グラフ）（みんなの声用）
/catchphrase_i    => キャッチフレーズ 入力
/catchphrase_w    => キャッチフレーズ 待ち
/catchphrase_s    => キャッチフレーズ 選出
/catchphrase_r    => キャッチフレーズ 結果（みんなの声にも流用）
/reporting_all_r  => 試乗系回答一覧（レーダーチャート）（みんなの声用）
/faq              => 勉強会Q&A
/examinations_1_q => 理解度確認テスト 質問（選択）
/examinations_1_c => 理解度確認テスト 確認
/examinations_1_f => 理解度確認テスト 回答完了
/enquetes_1_q     => アンケート 質問（選択）
/enquetes_1_c     => アンケート 確認
/enquetes_1_f     => アンケート 回答完了


API仕様
jsonフォーマット

・基本情報取得
送信
GET : /serverInfo
返り値
{
  "status": "success",
  "message": "success",
  "update": "2019-05-07 19:17:35",
  "updatestamp": 1557224255,
  "data": {
    "lecutureName": "勉強会2019"
  }
}

・セッション内容

QUESTION_DIV が問題の区分（種類）
qo=1問1択 qm=1問複数択 qc=比較設問 rp=レポート ex=テスト en=アンケート 

array (
  'question_atr' =>
  array (
    'questions_1' =>
    array (
      'QUESTION_TYPE' => 'questions_1',
      'QUESTION_DIV' => 'qo',
      'QUESTION_NAME' => 'オリエンテーション',
      'QUESTION_CNT' => '4',
      'QUESTION_EXPLANATION' => 'ddd',
      'QUESTION_COMPLETE' => false,
      'currentQuestionNo' => 1,
    ),
    'questions_2' =>
    array (
      'QUESTION_TYPE' => 'questions_2',
      'QUESTION_DIV' => 'qo',
      'QUESTION_NAME' => '座学',
      'QUESTION_CNT' => '0',
      'QUESTION_EXPLANATION' => NULL,
      'QUESTION_COMPLETE' => false,
      'currentQuestionNo' => 1,
    ),
    'questions_3' =>
    array (
      'QUESTION_TYPE' => 'questions_3',
      'QUESTION_DIV' => 'qc',
      'QUESTION_NAME' => 'まとめ①',
      'QUESTION_CNT' => '0',
      'QUESTION_EXPLANATION' => NULL,
      'QUESTION_COMPLETE' => false,
      'currentQuestionNo' => 1,
    ),
    'questions_4' =>
    array (
      'QUESTION_TYPE' => 'questions_4',
      'QUESTION_DIV' => 'qm',
      'QUESTION_NAME' => 'まとめ②',
      'QUESTION_CNT' => '0',
      'QUESTION_EXPLANATION' => NULL,
      'QUESTION_COMPLETE' => false,
      'currentQuestionNo' => 1,
    ),
    'reporting_1' =>
    array (
      'QUESTION_TYPE' => 'reporting_1',
      'QUESTION_DIV' => 'rp',
      'QUESTION_NAME' => '試乗①',
      'QUESTION_CNT' => '7',
      'QUESTION_EXPLANATION' => '新旧比較 現行 vs 新型',
      'QUESTION_COMPLETE' => false,
      'currentQuestionNo' => 1,
    ),
    'reporting_2' =>
    array (
      'QUESTION_TYPE' => 'reporting_2',
      'QUESTION_DIV' => 'rp',
      'QUESTION_NAME' => '試乗②',
      'QUESTION_CNT' => '4',
      'QUESTION_EXPLANATION' => '現行 vs ワゴンR vs 新型'
      'QUESTION_COMPLETE' => false,
      'currentQuestionNo' => 1,
    ),
    'reporting_3' =>
    array (
      'QUESTION_TYPE' => 'reporting_3',
      'QUESTION_DIV' => 'rp',
      'QUESTION_NAME' => '現車・競合車確認',
      'QUESTION_CNT' => '7',
      'QUESTION_EXPLANATION' => '新型 vs ワゴンR vs N-WGN
      'QUESTION_COMPLETE' => false,
      'currentQuestionNo' => 1,
    ),
    'examinations_1' =>
    array (
      'QUESTION_TYPE' => 'examinations_1',
      'QUESTION_DIV' => 'ex',
      'QUESTION_NAME' => '理解度確認テスト',
      'QUESTION_CNT' => '0',
      'QUESTION_EXPLANATION' => NULL,
      'QUESTION_COMPLETE' => false,
      'currentQuestionNo' => 1,
    ),
    'enquetes_1' =>
    array (
      'QUESTION_TYPE' => 'enquetes_1',
      'QUESTION_DIV' => 'en',
      'QUESTION_NAME' => 'アンケート',
      'QUESTION_CNT' => '0',
      'QUESTION_EXPLANATION' => '',
      'QUESTION_COMPLETE' => false,
      'currentQuestionNo' => 1,
    ),
  ),
  'member' =>
  array (
    'MEMBER_ID' => '1',
    'SEAT_CD' => '#01',
    'TERM_CD' => 'XXXXX',
    'GROUP_CD' => '#',
    'LECTURE_DT' => '0000-00-00',
    'LECTURE_TYPE' => '1',
    'COMPANY_CD' => NULL,
    'SITE_NAME' => NULL,
    'MEMBER_NAME' => '講師-前半(#01)',
    'COMPANY_NAME' => '日産自動車（株）',
    'PLACE_NAME' => NULL,
    'DIV_NAME' => NULL,
    'POST_NAME' => NULL,
    'LECTURE_NAME' => NULL,
    'INS_DT' => '2018-11-13 16:52:33',
    'UPD_DT' => '2019-04-01 16:27:16',
    'DEL_FLG' => '0',
  ),
)


・ログイン
送信
login
{
	su 
	date : 
}

・問題取得
{
  "status": "success",
  "message": "success",
  "update": "2019-05-14 11:49:23",
  "updatestamp": 1557802163,
  "data": {
    "QUESTION_STR": "現行デイズ、この１年でだいたい何台売った？",
    "QUESTION_NO": "1",
    "QUESTION_CNT": "4",
    "ANSWER_CNT": "4",
    "answers": [
      "0台",
      "1～5台",
      "6～9台",
      "10台以上"
    ],
    "selectedNo": null
  }
}


// 棒グラフ
        questionStr: "Q1. 現行フォレスター、この１年でだいたい何台売った？",
        answerList: ["①0台","②1～5台","③6～9台","④10台以上"],
        valueList: [25,75,0,0],
        sumList:[1,3,0,0],
        selected: 1 // 回答が単数選択の場合
        selectedList: [1,2]  // 回答が複数選択の場合は選択した回答番号をリストで


array (
  0 =>
  array (
    'QUESTION_ID' => '1',
    'QUESTION_STR' => '現行デイズ、この１年でだいたい何台売った？',
    'QUESTION_NO' => '1',
    'QUESTION_CNT' => '4',
    'ANSWER_CNT' => '4',
    'answerList' =>
    array (
      0 => '0台',
      1 => '1～5台',
      2 => '6～9台',
      3 => '10台以上',
    ),
    'selectedNo' => 2,
  ),
)



Enter押下時に自画面への遷移を防いで、なおかつ、ボタン押下時と同じふるまいにする。
そのために、Vuejsの機能を利用してsubmitイベントを黙殺して、自身が作成したハンドラを実行する。
<template>
  <form @submit.prevent="exec">
    <input type="text" placeholder="username" />
    <input type="text" placeholder="password" />
    <button type="submit">submit</button>
  </form>
</template>

<script>
export default {
  name: 'HelloWorld',
  methods: {
    exec: function () {
      // 本来はajax通信をする
      console.log('exec')
    }
  }
}
</script>


VueJS ページジャンプ
https://router.vuejs.org/ja/guide/essentials/navigation.html

Vue.jsの書き方実例集(随時追加)※逆引きリファレンス的な
https://qiita.com/Yorinton/items/a0144c34e4edb0777493
