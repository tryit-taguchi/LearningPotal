<?php
/**
 * 設問 - 登録
 *
 * 設問 - 登録をします
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
$questionType    = getQuestionType();    // 設問属性リスト取得
$questionAtrList = getQuestionAtrList(); // 設問属性取得
$questionAtr     = getQuestionAtr();     // 設問種別をファイル名から取得

//--------------------------------------
// コントローラー
//--------------------------------------
switch($nextProcess) {
	default:
		$form = array();
		$form['lastUpdate'] = $questionAtr['UPD_DT'];
		$data  = cQuestion::getData($lectureName,$questionType);
		$qAtr  = $data['atr'];
		$qList = $data['list'];
		$no    = count($qList)+1;
		for($i = $no; $i<=15; $i++) {
			$rec = array();
			$rec['QUESTION_NO'] = $i;
			$qList[$i] = $rec;
		}
		// QUESTION_DIV(質問区分) qo=1問1択 qm=1問複数択 qc=比較設問 rp=レポート ex=テスト en=アンケート
		$tmp = cMasterQuestionAtr::getList(array("LECTURE_NAME"=>$lectureName,"QUESTION_DIV"=>'qo'));
		$qTypeList = array();
		foreach($tmp as $atr) {
			$qTypeList[$atr['QUESTION_TYPE']] = $atr['QUESTION_NAME'];
		}
		include("template/entry.html");
		//defaultTemplate($form); // php と主ファイル名が同じhtmlをtemplate/からインクルードする
		break;
	case "dataImport": // インポート
		fLog($_FILES);
		cQuestion::importData($lectureName,$questionType,$_FILES);
		break;
	case "dataExport": // エクスポート
		cQuestion::exportData($lectureName,$questionType);
		break;
	case "dataSave": // 保存
		cQuestion::saveData($lectureName,$questionType,$REQUEST->post);
		break;
}
?>
