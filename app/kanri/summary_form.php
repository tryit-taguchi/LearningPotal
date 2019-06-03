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

//--------------------------------------
// 内部データの取得
//--------------------------------------
$db = new cDbExt();
$sql = "select LECTURE_DT,count(MEMBER_ID) as MEMBER_CNT,max(SITE_NAME) as SITE_NAME from M_MEMBER where TERM_CD not like 'K%' group by LECTURE_DT,SITE_NAME order by LECTURE_DT desc";
$lectureDateList = $db->getList($sql);

//--------------------------------------
// コントローラー
//--------------------------------------

//-------------------------------------
// 表示処理
//-------------------------------------
include("template/summary_form.html");
?>
