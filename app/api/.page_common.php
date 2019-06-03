<?php
/**
 * 各ページ共通の処理
 *
 * 各ページ共通の処理をします
 * 
 * @access public
 * @author Daisuke Sasaki
 * @copyright Copyright (c) 2015, Tryit
 * @version 1.00
 * @since 2015/12/1
 */
/**
 */

// 基本データの取得
function getBaseData() {
	global $__lectureName,$__questionType,$__questionAtrList,$__questionAtr;
	$__lectureName     = cConfig::getValue("研修名");
	$__questionType    = str_replace('entry_','',pathinfo($_SERVER['PHP_SELF'],PATHINFO_FILENAME)); // 質問タイプ
	$__questionAtrList = cMasterQuestionAtr::getList(array('LECTURE_NAME'=>$__lectureName));
	$__questionAtr     = $__questionAtrList[$__questionType];
}
getBaseData();
// 研修名取得
function getLectureName() {
	global $__lectureName;
	return $__lectureName;
}
// 設問種別をファイル名から取得
function getQuestionType() {
	global $__questionType;
	return $__questionType;
}
// 設問属性リスト取得
function getQuestionAtrList() {
	global $__questionAtrList;
	return $__questionAtrList;
}
// 設問属性取得
function getQuestionAtr() {
	global $__questionAtr;
	return $__questionAtr;
}
// 基本変数取得
$lectureName     = getLectureName();     // 研修名取得
$questionType    = getQuestionType();    // 設問属性リスト取得
$questionAtrList = getQuestionAtrList(); // 設問属性取得
$questionAtr     = getQuestionAtr();     // 設問種別をファイル名から取得

//dout($questionAtrList);
// url上のディレクトリ名を取得
function getUrlDir() {
	return dirname($_SERVER['REQUEST_URI']);
}
