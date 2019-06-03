<?php
/**
 * レポート出力表示
 *
 * レポート出力表示をします
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
$lectureType = (int)$tmp[2];
$termCd    = $form['termCd'];
$nameWord  = $form['nameWord'];
$siteId    = hash('ripemd160',$lectureDt.$siteName);

//--------------------------------------
// 内部データの取得
//--------------------------------------
$lectureName  = cConfig::getValue("研修名");
$answerDate   = $lectureDt; // 回答日付

$db = new cDbExt();
// 該当のメンバーを取得
$sql = "select * from M_MEMBER where LECTURE_DT='{$lectureDt}' and SITE_NAME='{$siteName}' and LECTURE_TYPE={$lectureType} ";
if( !empty($termCd) ) {
	$sql .= " and TERM_CD like '%{$termCd}%' ";
}
if( !empty($nameWord) ) {
	$sql .= " and concat(ifnull(MEMBER_NAME,''),ifnull(COMPANY_NAME,'')) like '%{$nameWord}%' ";
}
$sql .= " and TERM_CD not like 'K%' ";
$sql .= " order by TERM_CD";
$memberList = $db->getList($sql);
//dout($memberList);

// 試乗１の回答集計を出す
$cond = array();
$cond['LECTURE_NAME']  = $lectureName;
$cond['QUESTION_TYPE'] = 4; // 質問タイプ 1=意識 3=現車 4=試乗1 5=試乗2 6=アンケート
list($qIds,$questionTrial1List) = aggregateCheckQuestion(cMasterQuestion::getList($cond,"lpad(QUESTION_CD,10,'0')"),$cond['QUESTION_TYPE']);
$answerType = 4; // 回答タイプ 1=前意識 2=後意識 3=現車 4=試乗1 5=試乗2 6=アンケート
$totalAnswerTrial1List = aggregateCheckTotal($qIds,$answerType); // 全体の回答を取得
$groupAnswerTrial1List = aggregateCheckGroup($qIds,$answerType,$answerDate,$siteName);
foreach( $memberList as &$member ) {
	$member['memberAnswerTrial1List'] = aggregateCheckMember($qIds,$answerType,$answerDate,$member['MEMBER_ID']);
}
unset($member);  // これが重要

// 試乗２の回答集計を出す
$cond = array();
$cond['LECTURE_NAME']  = $lectureName;
$cond['QUESTION_TYPE'] = 5; // 質問タイプ 1=意識 3=現車 4=試乗1 5=試乗2 6=アンケート
list($qIds,$questionTrial2List) = aggregateCheckQuestion(cMasterQuestion::getList($cond,"lpad(QUESTION_CD,10,'0')"),$cond['QUESTION_TYPE']);
$answerType = 5; // 回答タイプ 1=前意識 2=後意識 3=現車 4=試乗1 5=試乗2 6=アンケート
$totalAnswerTrial2List = aggregateCheckTotal($qIds,$answerType); // 全体の回答を取得
$groupAnswerTrial2List = aggregateCheckGroup($qIds,$answerType,$answerDate,$siteName);
foreach( $memberList as &$member ) {
	$member['memberAnswerTrial2List'] = aggregateCheckMember($qIds,$answerType,$answerDate,$member['MEMBER_ID']);
}
unset($member);  // これが重要

// 現車確認の回答集計を出す
$cond = array();
$cond['LECTURE_NAME']  = $lectureName;
$cond['QUESTION_TYPE'] = 3; // 質問タイプ 1=意識 3=現車 4=試乗1 5=試乗2 6=アンケート
list($qIds,$questionRealList) = aggregateCheckQuestion(cMasterQuestion::getList($cond,"lpad(QUESTION_CD,10,'0')"),$cond['QUESTION_TYPE']);
$answerType = 3; // 回答タイプ 1=前意識 2=後意識 3=現車 4=試乗1 5=試乗2 6=アンケート
$totalAnswerRealList = aggregateCheckTotal($qIds,$answerType); // 全体の回答を取得
$groupAnswerRealList = aggregateCheckGroup($qIds,$answerType,$answerDate,$siteName);

foreach( $memberList as &$member ) {
	$member['memberAnswerRealList'] = aggregateCheckMember($qIds,$answerType,$answerDate,$member['MEMBER_ID']);
}
unset($member);  // これが重要

// （旧意識調査）まとめの回答集計を出す
$cond = array();
$cond['LECTURE_NAME']  = $lectureName;
$cond['QUESTION_TYPE'] = 1; // 質問タイプ 1=意識 3=現車 4=試乗1 5=試乗2 6=アンケート
list($qIds,$questionSenseList) = aggregateSenseQuestion(cMasterQuestion::getList($cond,"lpad(QUESTION_CD,10,'0')"),$cond['QUESTION_TYPE']);
$answerType = 1; // 回答タイプ 1=前意識 2=後意識 3=現車 4=試乗1 5=試乗2 6=アンケート
$totalAnswerSenseList = aggregateSenseTotal($qIds,$answerType); // 全体の回答を取得
$groupAnswerSenseList = aggregateSenseGroup($qIds,$answerType,$answerDate,$siteName,$lectureType);

foreach( $memberList as &$member ) {
	$member['memberAnswerSenseList'] = aggregateSenseMember($qIds,$answerType,$answerDate,$member['MEMBER_ID']);
}

unset($member);  // これが重要

//--------------------------------------
// コントローラー
//--------------------------------------
switch($nextProcess) {
	default:
		break;
}

//-------------------------------------
// 表示処理
//-------------------------------------
include("template/report_output.html");
?>
