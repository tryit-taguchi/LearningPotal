<?php
/**
 * 会場 - 登録
 *
 * 会場 - 登録をします
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
$nextProcess    = strim($REQUEST->val["nextProcess"]);
$reqVal         = strim($REQUEST->val["val"]);

//--------------------------------------
// 内部データの取得
//--------------------------------------
$lectureName     = getLectureName();     // 研修名取得

//--------------------------------------
// コントローラー
//--------------------------------------
switch($nextProcess) {
	default:
		$form = array();
		$siteList = cSite::getData($lectureName);
		$no    = count($siteList)+1;
		for($i = $no; $i<=15; $i++) {
			$rec = array();
			$rec['SITE_NO'] = $i;
			$siteList[$i] = $rec;
		}
		defaultTemplate($form); // php と主ファイル名が同じhtmlをtemplate/からインクルードする
		break;
	case "dataSave": // 保存
		cSite::saveData($lectureName,$REQUEST->post);
		break;
}
?>
