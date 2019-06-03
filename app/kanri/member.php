<?php
/**
 * 受講者登録
 *
 * 受講者登録をします
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
$form           = $REQUEST->val;
$nextProcess    = strim($form["nextProcess"]);
$reqVal         = strim($form["val"]);
$editLectureAtr     = $form['editLectureAtr'];
$tmp = explode('@',$editLectureAtr);
$lectureDt      = $tmp[0];
$siteName       = $tmp[1];

//--------------------------------------
// 内部データの取得
//--------------------------------------
$lectureName = getLectureName();     // 研修名取得
$memberLast = "受講者登録_最終更新日時";
$lastUpdate = cConfig::getValue($memberLast);

$db = new cDbExt();
$sql = "select LECTURE_DT,count(MEMBER_ID) as MEMBER_CNT,max(SITE_NAME) as SITE_NAME from M_MEMBER where TERM_CD not like 'K%' group by LECTURE_DT,SITE_NAME order by LECTURE_DT desc";
$lectureDateList = $db->getList($sql);

//dout($lectureDateList);
//--------------------------------------
// コントローラー
//--------------------------------------
switch($nextProcess) {
	default:
		$form = array();
		$form['lastUpdate'] = $lastUpdate;
		if( !empty($editLectureAtr) ) {
			$memberList = cMember::getData($lectureName,$lectureDt,$siteName);
		}
		defaultTemplate($form); // php と主ファイル名が同じhtmlをtemplate/からインクルードする
		break;
	case "dataImport":
		cMember::importData($lectureName,$_FILES,$memberLast);
		break;
	case "dataExport":
		$lectureAtr     = $form['expLectureAtr'];
		$tmp = explode('@',$lectureAtr);
		$lectureDt      = $tmp[0];
		$siteName       = $tmp[1];
		cMember::exportData($lectureName,$lectureDt,$siteName,$memberLast);
		break;
	case "dataSave": // 保存
		cMember::saveData($lectureName,$lectureDt,$siteName,$REQUEST->post,$memberLast);
		break;
}
?>
