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

/*
.\wkhtmltopdf.exe --page-size A4 --disable-smart-shrinking --orientation Landscape  http://192.168.0.5/nsp/kanri/report_view.php?lectureAtr=2018-11-05@追浜グランドライブ test.pdf
.\wkhtmltopdf.exe --page-size A4 --viewport-size 1024x760 --orientation Landscape  http://192.168.0.5/nsp/kanri/report_view.php?lectureAtr=2018-11-05@追浜グランドライブ test.pdf
.\wkhtmltopdf.exe --page-size A4 --margin-top 3 --margin-right 0 --margin-left 3 --margin-bottom 0 --disable-smart-shrinking --orientation Landscape  http://192.168.0.5/nsp/kanri/report_view.php?lectureAtr=2018-11-05@追浜グランドライブ test.pdf

http://192.168.0.5/nsp/kanri/report_view.php?lectureAtr=2018-11-15@十勝スピードウェイ@1
http://localhost/nsp/kanri/report_view.php?lectureAtr=2018-11-15@十勝スピードウェイ@1
http://localhost/nsp/kanri/report_view.php?lectureAtr=2018-11-15%40%E5%8D%81%E5%8B%9D%E3%82%B9%E3%83%94%E3%83%BC%E3%83%89%E3%82%A6%E3%82%A7%E3%82%A4%401&termCd=&nameWord=&lectureType=1

select * from M_MEMBER where LECTURE_DT='2018-11-15' and SITE_NAME='十勝スピードウェイ' and LECTURE_TYPE=1
  and TERM_CD not like 'K%'  order by SEAT_CD,TERM_CD
*/
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
$sql .= " order by SEAT_CD,TERM_CD";
$memberList = $db->getList($sql);

// （旧意識調査）まとめの回答集計を出す
$cond = array();
$cond['LECTURE_NAME']  = $lectureName;
$cond['QUESTION_TYPE'] = 1; // 質問タイプ 1=意識 3=現車 4=試乗1 5=試乗2 6=アンケート
list($qIds,$questionSenseList) = aggregateSenseQuestion(cMasterQuestion::getList($cond,"lpad(QUESTION_CD,10,'0')"),$cond['QUESTION_TYPE']);
$answerType = 1; // 回答タイプ 1=前意識 2=後意識 3=現車 4=試乗1 5=試乗2 6=アンケート
foreach( $memberList as &$member ) {
	$member['memberAnswerSenseList'] = aggregateSenseMember($qIds,$answerType,$answerDate,$member['MEMBER_ID']);
}

// フリーコメントを抽出する
foreach( $memberList as &$member ) {
	$memberId = $member['MEMBER_ID'];
	$sql = "select COMMENT_TYPE,COMMENT_STR from T_COMMENT,M_MEMBER where T_COMMENT.MEMBER_ID=M_MEMBER.MEMBER_ID and M_MEMBER.MEMBER_ID={$memberId} ";
	$list = $db->getList($sql);
	foreach($list as $rec) {
		$member['freeComment'][$rec['COMMENT_TYPE']] = $rec['COMMENT_STR'];
	}
}
unset($member);  // これが重要

// キャッチフレーズ（本日）
/*
$cond = array();
$cond['CATCHPHRASE_DT']  = $lectureDt;
$cond['LECTURE_NAME']  = $lectureName;
$catchphraseList = cTransactionCatchword::getList($cond,"VOTE_CNT desc",3);
*/
$sql = <<< SQL
select *
  from T_CATCHPHRASE,M_MEMBER
 where T_CATCHPHRASE.MEMBER_ID=M_MEMBER.MEMBER_ID
   and T_CATCHPHRASE.LECTURE_NAME='{$lectureName}'
   and T_CATCHPHRASE.CATCHPHRASE_DT='{$lectureDt}'
   and M_MEMBER.SITE_NAME='{$siteName}'
   and M_MEMBER.LECTURE_TYPE={$lectureType}
 order by VOTE_CNT desc,CATCHPHRASE_ID
SQL;
$db = new cDbExt();
$catchphraseList = $db->getList($sql);

// キャッチフレーズ（過去）
$db = new cDbExt();
$sql = <<< SQL
  select M_MEMBER.MEMBER_ID,
         CATCHPHRASE_DT,
         ifnull(M_MEMBER.SITE_NAME,'会場未定') as SITE_NAME,
         LECTURE_TYPE,
         VOTE_CNT,
         CATCHPHRASE_DT,
         CATCHPHRASE_STR
    from T_CATCHPHRASE,M_MEMBER
   where T_CATCHPHRASE.MEMBER_ID=M_MEMBER.MEMBER_ID
     and T_CATCHPHRASE.LECTURE_NAME='{$lectureName}'
   order by CATCHPHRASE_DT desc,SITE_NAME,VOTE_CNT desc,CATCHPHRASE_ID
SQL;
$list = $db->getList($sql);
//dout($list);
$lectureTypeStrList = array( 1 => '前半', 2 => '後半');
$catchphraseTmpList = array();
$chunk =  3; // １行に出すグループ数
$total = 27; // 会場数
foreach($list as $rec) {
	$key            = $rec['CATCHPHRASE_DT'].'@'.$rec['SITE_NAME'];
	$lectureTypeStr = $lectureTypeStrList[$rec['LECTURE_TYPE']];
	if( !isset($catchphraseTmpList[$key][$lectureTypeStr]) ) {
		$catchphraseTmpList[$key][$lectureTypeStr] = str_replace("\n"," ",$rec['CATCHPHRASE_STR']);
	}
}
// 前会場分埋める
$cnt = count($catchphraseTmpList);
for( $i=$cnt;$i<$total;$i++) {
	$catchphraseTmpList[$i] = array();
}
// 前半後半の空を埋める
foreach($catchphraseTmpList as $key => &$rec) {
	if( !isset($catchphraseTmpList[$key]['前半']) ) { $catchphraseTmpList[$key]['前半']=''; }
	if( !isset($catchphraseTmpList[$key]['後半']) ) { $catchphraseTmpList[$key]['後半']=''; }
}
unset($rec);

$catchphraseOldList = array_chunk($catchphraseTmpList,$chunk,true);
//dout($catchphraseOldList);

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
include("template/report_view.html");
?>
