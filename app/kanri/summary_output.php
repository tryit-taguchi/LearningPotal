<?php
/**
 * 集計結果表示
 *
 * 集計結果表示をします
 *
 * @access public
 * @author Atsushi Taguchi
 * @copyright Copyright (c) 2017, Tryit
 * @version 1.00
 * @since 2017/05/02
 */
/**
 */
require(".site_conf.php");
require("lib/core/iCommon.php");            // 基本クラス
require(".page_common.php");

//--------------------------------------
// ブロック処理
//--------------------------------------

//--------------------------------------
// 外部データの取得
//--------------------------------------
$form = $REQUEST->val;
$lectureAtr = $form['lectureAtr'];
$tmp = explode('@',$lectureAtr);
$lectureDt = $tmp[0];
$siteName  = $tmp[1];
$termCd    = $form['termCd'];
$nameWord  = $form['nameWord'];
$siteId    = hash('ripemd160',$lectureDt.$siteName);
$answerType = $form['answerType'];

//--------------------------------------
// 内部データの取得
//--------------------------------------
$lectureName  = cConfig::getValue("研修名");
$answerDate   = $lectureDt; // 回答日付

$answerTypeStr = array(
	1 => '意識確認',
	3 => '現車確認',
	4 => '試乗①',
	5 => '試乗②',
	6 => 'アンケート',
	7 => '理解度テスト',
);
$answerTypeExcelFile = array(
	1 => 'answer_sense.xlsx',
	3 => 'answer_physical.xlsx',
	4 => 'answer_physical.xlsx',
	5 => 'answer_physical.xlsx',
	6 => 'answer_enquete.xlsx',
	7 => 'answer_understanding.xlsx',
);
// コメントシート trueで非表示 falseで表示
$answerTypeExcelCommentHidden = array(
	1 => true,    // 意識確認
	3 => false,   // 現車確認
	4 => false,   // 試乗①
	5 => false,   // 試乗②
	6 => false,   // アンケート
	7 => true,    // 理解度テスト
);
// 集計シート trueで非表示 falseで表示
$answerTypeExcelSummaryHidden = array(
	1 => true,    // 意識確認
	3 => true,    // 現車確認
	4 => true,    // 試乗①
	5 => true,    // 試乗②
	6 => false,   // アンケート
	7 => true,    // 理解度テスト
);

$db = new cDbExt();
// 該当のメンバーを取得
$sql = "select * from M_MEMBER where LECTURE_DT='{$lectureDt}' and SITE_NAME='{$siteName}' ";
if( !empty($termCd) ) {
	$sql .= " and TERM_CD like '%{$termCd}%' ";
}
if( !empty($nameWord) ) {
	$sql .= " and concat(ifnull(MEMBER_NAME,''),ifnull(COMPANY_NAME,'')) like '%{$nameWord}%' ";
}
$sql .= " and TERM_CD not like 'K%' ";
$sql .= " order by TERM_CD";
$memberList = $db->getList($sql);
// 人数計算
$memberTotal = 0;
$memberCnt1 = 0;
$memberCnt2 = 0;
foreach($memberList as $member) {
	if( $member['LECTURE_TYPE']==1 ) {
		$memberCnt1++;
	}
	if( $member['LECTURE_TYPE']==2 ) {
		$memberCnt2++;
	}
}
$memberTotal = $memberCnt1+$memberCnt2;

//dout($memberList);
// ID取得
$memberIdList = array();
foreach($memberList as $member) {
	$memberIdList[] = $member['MEMBER_ID'];
}
$memberIds = join(",",$memberIdList);
// 設問を取得
$sql = "select * from M_QUESTION where LECTURE_NAME='{$lectureName}' and QUESTION_TYPE={$answerType} order by lpad(QUESTION_CD,10,'0') ";
$tmpList = $db->getList($sql);
$quetionList = array();
foreach($tmpList as $rec) {
	$quetionList[$rec['QUESTION_ID']] = $rec;
}

//dout($quetionList);

// 設問を取得
$sql = <<< SQL
select *,
	T_ANSWER.ANSWER_0 as A0,
	T_ANSWER.ANSWER_1 as A1,
	T_ANSWER.ANSWER_2 as A2,
	T_ANSWER.ANSWER_3 as A3,
	T_ANSWER.ANSWER_4 as A4,
	T_ANSWER.ANSWER_5 as A5,
	T_ANSWER.ANSWER_6 as A6,
	T_ANSWER.ANSWER_7 as A7,
	T_ANSWER.ANSWER_8 as A8,
	T_ANSWER.ANSWER_9 as A9
 from
	 T_ANSWER,
	 M_MEMBER,
	 M_QUESTION
 where T_ANSWER.QUESTION_ID=M_QUESTION.QUESTION_ID
   and T_ANSWER.MEMBER_ID=M_MEMBER.MEMBER_ID
   and ANSWER_TYPE={$answerType}
   and ANSWER_DT='{$lectureDt}'
   and T_ANSWER.MEMBER_ID in ($memberIds)
 order by TERM_CD,lpad(M_QUESTION.QUESTION_CD,10,'0')
SQL;
$answerList = $db->getList($sql);
// コメントを取得
$sql = <<< SQL
select *
 from
	 T_COMMENT,
	 M_MEMBER
 where T_COMMENT.MEMBER_ID=M_MEMBER.MEMBER_ID
   and COMMENT_TYPE={$answerType}
   and COMMENT_DT='{$lectureDt}'
 order by TERM_CD
SQL;
$commentList = $db->getList($sql);

//--------------------------------------
// コントローラー
//--------------------------------------

switch($nextProcess) {
	default:
		$cond = array();
		$cond['LECTURE_NAME']  = $lectureName;
		$cond['QUESTION_TYPE'] = $questionType; // 質問タイプ 1=意識 3=現車 4=試乗1 5=試乗2 6=アンケート
		$list = cMasterQuestion::getList($cond,"lpad(QUESTION_CD,10,'0')");
		cExcel::load("template/excel/".$answerTypeExcelFile[$answerType]);
		cExcel::setActiveSheetIndex(0);
		cExcel::cell( 1, 1,$answerTypeStr[$answerType] . " 回答一覧");

		$no  = 3;
		foreach($answerList as $rec) {
			$col = 1;
			$question = $quetionList[$rec['QUESTION_ID']];
			cExcel::cell($col++, $no,$rec['LECTURE_DT']);
			cExcel::cell($col++, $no,$rec['TERM_CD']);
			cExcel::cell($col++, $no,$rec['LECTURE_TYPE']==1?'前半':'後半');
			cExcel::cell($col++, $no,$rec['COMPANY_NAME']);
			cExcel::cell($col++, $no,$rec['MEMBER_NAME']);
			cExcel::cell($col++, $no,"Q".$question['QUESTION_CD'].".".$question['QUESTION_STR']);
			cExcel::cell($col++, $no,$question['ANSWER_0']);
			cExcel::cell($col++, $no,$question['ANSWER_1']);
			cExcel::cell($col++, $no,$question['ANSWER_2']);
			cExcel::cell($col++, $no,$question['ANSWER_3']);
			cExcel::cell($col++, $no,$question['ANSWER_4']);
			cExcel::cell($col++, $no,$question['ANSWER_5']);
			cExcel::cell($col++, $no,$question['ANSWER_6']);
			cExcel::cell($col++, $no,$question['ANSWER_7']);
			cExcel::cell($col++, $no,$question['ANSWER_8']);
			cExcel::cell($col++, $no,$question['ANSWER_9']);
			cExcel::cell($col++, $no,$rec['A0']==0?'':$rec['A0']);
			cExcel::cell($col++, $no,$rec['A1']==0?'':$rec['A1']);
			cExcel::cell($col++, $no,$rec['A2']==0?'':$rec['A2']);
			cExcel::cell($col++, $no,$rec['A3']==0?'':$rec['A3']);
			cExcel::cell($col++, $no,$rec['A4']==0?'':$rec['A4']);
			cExcel::cell($col++, $no,$rec['A5']==0?'':$rec['A5']);
			cExcel::cell($col++, $no,$rec['A6']==0?'':$rec['A6']);
			cExcel::cell($col++, $no,$rec['A7']==0?'':$rec['A7']);
			cExcel::cell($col++, $no,$rec['A8']==0?'':$rec['A8']);
			cExcel::cell($col++, $no,$rec['A9']==0?'':$rec['A9']);
			$no++;
		}
		cExcel::setActiveSheetIndex(1);
		cExcel::cell( 1, 1,$answerTypeStr[$answerType] . " コメント");
		$no  = 3;
		foreach($commentList as $rec) {
			$col = 1;
			$question = $quetionList[$rec['QUESTION_ID']];
			cExcel::cell($col++, $no,$rec['LECTURE_DT']);
			cExcel::cell($col++, $no,$rec['TERM_CD']);
			cExcel::cell($col++, $no,$rec['LECTURE_TYPE']==1?'前半':'後半');
			cExcel::cell($col++, $no,$rec['COMPANY_NAME']);
			cExcel::cell($col++, $no,$rec['MEMBER_NAME']);
			cExcel::cell($col++, $no,$rec['COMMENT_STR']);
			$no++;
		}
		// 種別によってシートを非表示にする
		cExcel::hiddenActiveSheet($answerTypeExcelCommentHidden[$answerType]);

		cExcel::setActiveSheetIndex(2);
		cExcel::cell( 1, 1,$answerTypeStr[$answerType] . " 集計(速報)");
		cExcel::cell( 2, 3,$memberTotal);
		cExcel::cell( 4, 3,$memberCnt1);
		cExcel::cell( 6, 3,$memberCnt2);
		// 種別によってシートを非表示にする
		cExcel::hiddenActiveSheet($answerTypeExcelSummaryHidden[$answerType]);

		cExcel::setActiveSheetIndex(0);

		$tmpDir = sys_get_temp_dir();
		$exceiFileName = "回答_{$answerTypeStr[$answerType]}_{$lectureDt}.xlsx";
		cExcel::save($tmpDir."/{$exceiFileName}");
		// ダウンロードさせる
		cUtility::download($tmpDir."/{$exceiFileName}",$exceiFileName);
		break;
}

//-------------------------------------
// 表示処理
//-------------------------------------
?>
