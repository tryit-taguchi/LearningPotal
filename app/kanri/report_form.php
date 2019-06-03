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

//--------------------------------------
// 内部データの取得
//--------------------------------------
$db = new cDbExt();
$sql = "select LECTURE_DT,count(MEMBER_ID) as MEMBER_CNT,max(SITE_NAME) as SITE_NAME,LECTURE_TYPE from M_MEMBER where TERM_CD not like 'K%' group by LECTURE_DT,SITE_NAME,LECTURE_TYPE order by LECTURE_DT desc";
$lectureDateList = $db->getList($sql);
//dout($lectureDateList);

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
include("template/report_form.html");
?>
