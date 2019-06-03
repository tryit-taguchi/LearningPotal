<?php
/**
 * トップ表示
 *
 * トップ表示をします
 *
 * @access public
 * @author Atsushi Taguchi
 * @copyright Copyright (c) 2018, Tryit
 * @version 1.00
 * @since 2018/09/05
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

//--------------------------------------
// コントローラー
//--------------------------------------
$lastUpdate = "";
switch($nextProcess) {
	default:
		$form = array();
		$form["研修名"]   = cConfig::getData("研修名");
		$form["管理メモ"] = cConfig::getData("管理メモ");
		defaultTemplate($form); // php と主ファイル名が同じhtmlをtemplate/からインクルードする
		break;
	case "lectureNameSave":
		cConfig::setData("研修名",$reqVal);
		$data = cConfig::getData("研修名");
		$lastUpdate = $data["UPD_DT"];
		$result = new StdClass();
		$result->status = "success";
		$result->result = new StdClass();
		$result->result->lastUpdate = $lastUpdate;
		echo json_encode($result);
		break;
	case "lectureMemoSave":
		cConfig::setData("管理メモ",$reqVal);
		$data = cConfig::getData("管理メモ");
		$lastUpdate = $data["UPD_DT"];
		$result = new StdClass();
		$result->status = "success";
		$result->result = new StdClass();
		$result->result->lastUpdate = $lastUpdate;
		echo json_encode($result);
		break;
}
?>
