<?php
/**
 * 設問 - 登録
 *
 * 設問 - 登録をします
 *
 * @access public
 * @author Atsushi Taguchi
 * @copyright Copyright (c) 2019, Tryit
 * @version 1.00
 * @since 2019/05/07
 */
/**
 例： http://nissan.t4u.bz/nlp/app/api/session/1
      http://nissan.t4u.bz/nlp/app/api/questions_1_a/1/1
      http://nissan.t4u.bz/nlp/app/api/questions_1_r/1/1
      http://nissan.t4u.bz/nlp/app/api/reporting_1_q/1/1
      http://nissan.t4u.bz/nlp/app/api/examinations_1_q
      http://nissan.t4u.bz/nlp/app/api/enquetes_1_q
 */
$sessionDisable = true;
require(".site_conf.php");
require("lib/core/iCommon.php");            // 基本クラス
require(".page_common.php");

preg_match('|' . dirname($_SERVER['SCRIPT_NAME']) . '/([\w%/]*)|', $_SERVER['REQUEST_URI'], $matches);
$paths = explode('/', $matches[1]);
fLog($paths);

$param1 = isset($paths[1]) ? htmlspecialchars($paths[1]) : null;
$param2 = isset($paths[2]) ? htmlspecialchars($paths[2]) : null;
$param3 = isset($paths[3]) ? htmlspecialchars($paths[3]) : null;
$routeType = $paths[0] . ':' . strtolower($_SERVER['REQUEST_METHOD']);

fLog("--------------------------" . $routeType);
fLog((empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
//fLog($_POST);
//fLog($_GET);
//fLog($_COOKIE);
/*
	'questions_1'    => 'オリエンテーション',
	'questions_2'    => '座学',
	'questions_3'    => 'まとめ①',
	'questions_4'    => 'まとめ②',
	'reporting_1'    => '試乗①',
	'reporting_2'    => '試乗②',
	'reporting_3'    => '現車・競合車確認',
	'reporting_4'    => '（予備）',
	'examinations_1' => '理解度確認テスト',
	'enquetes_1'     => 'アンケート',
*/
$memberId   = $param1;
$questionNo = $param2;

switch ($routeType) {
	case 'serverInfo:get':
		cAPI::getServerInfo();
		break;
	case 'session:get':
		cAPI::getSession($memberId);
		break;
	case 'session:post':
		cAPI::postSession($memberId);
		break;
	case 'login:post':
		cAPI::postLogin();
		break;
	case 'questions_1_q:get':
		cAPI::getQuestion('questions_1',$memberId,$questionNo,false);
		break;
	case 'questions_1_a:get':
		cAPI::getQuestion('questions_1',$memberId,$questionNo,true);
		break;
	case 'questions_1_r:get':
		cAPI::getQuestionList('questions_1',$memberId,true);
		break;
	case 'questions_1:post':
		cAPI::postQuestion('questions_1',$memberId);
		break;
	case 'questions_2_q:get':
		cAPI::getQuestion('questions_2',$memberId,$questionNo,false);
		break;
	case 'questions_2_a:get':
		cAPI::getQuestion('questions_2',$memberId,$questionNo,true);
		break;
	case 'questions_2_r:get':
		cAPI::getQuestionList('questions_2',$memberId,true);
		break;
	case 'questions_2:post':
		cAPI::postQuestion('questions_2',$memberId);
		break;
	case 'questions_3_q:get':
		cAPI::getQuestion('questions_3',$memberId,$questionNo,false);
		break;
	case 'questions_3_a:get':
		cAPI::getQuestion('questions_3',$memberId,$questionNo,true);
		break;
	case 'questions_3_r:get':
		cAPI::getQuestionList('questions_3',$memberId,true);
		break;
	case 'questions_3:post':
		cAPI::postQuestion('questions_3',$memberId);
		break;
	case 'questions_4_q:get':
		cAPI::getQuestion('questions_4',$memberId,$questionNo,false);
		break;
	case 'questions_4_a:get':
		cAPI::getQuestion('questions_4',$memberId,$questionNo,true);
		break;
	case 'questions_4_r:get':
		cAPI::getQuestionList('questions_4',$memberId,true);
		break;
	case 'questions_4:post':
		cAPI::postQuestion('questions_4',$memberId);
		break;
	case 'reporting_1_q:get':
		cAPI::getQuestionList('reporting_1',$memberId,false);
		break;
	case 'reporting_1_a:get':
		cAPI::getQuestionList('reporting_1',$memberId,true);
		break;
	case 'reporting_1:post':
		cAPI::postQuestion('reporting_1',$memberId);
		break;
	case 'reporting_2_q:get':
		cAPI::getQuestionList('reporting_2',$memberId,false);
		break;
	case 'reporting_2_a:get':
		cAPI::getQuestionList('reporting_2',$memberId,true);
		break;
	case 'reporting_2:post':
		cAPI::postQuestion('reporting_2',$memberId);
		break;
	case 'reporting_3_q:get':
		cAPI::getQuestionList('reporting_3',$memberId,false);
		break;
	case 'reporting_3_a:get':
		cAPI::getQuestionList('reporting_3',$memberId,true);
		break;
	case 'reporting_3:post':
		cAPI::postQuestion('reporting_3',$memberId);
		break;
	case 'reporting_4_q:get':
		cAPI::getQuestion('reporting_4',$memberId,$questionNo,false);
		break;
	case 'reporting_4_a:get':
		cAPI::getQuestion('reporting_4',$memberId,$questionNo,true);
		break;
	case 'reporting_4:post':
		cAPI::postQuestion('reporting_4',$memberId);
		break;
	case 'examinations_1_q:get':
		cAPI::getQuestionList('examinations_1',$memberId);
		break;
	case 'examinations_1:post':
		cAPI::postQuestion('examinations_1',$memberId);
		break;
	case 'enquetes_1_q:get':
		cAPI::getQuestionList('enquetes_1',$memberId);
		break;
	case 'enquetes_1:post':
		cAPI::postQuestion('enquetes_1',$memberId);
		break;
	case 'catchphrase_i:post':
		cAPI::postCatchphraseInput($memberId);
		break;
	case 'catchphrase_s:get':
		cAPI::getCatchphraseSelect($memberId);
		break;
	case 'catchphrase_s:post':
		cAPI::postCatchphraseSelect($memberId);
		break;
	case 'catchphrase_r:get':
		cAPI::getCatchphraseResult($memberId);
		break;
	case 'faq:get':
		cAPI::getFaqList('faq',$memberId);
		break;
	case 'config:get':
		cAPI::getConfig();
		break;
	case 'config:post':
		cAPI::postConfig();
		break;

// キャッチフレーズとかも追加

/*
	case 'get:user':
		if ($id) json_encode(array("ユーザー #{$id} 取得"));
		else echo 'ユーザー 一覧';
		break;
	case 'post:user':
		$json_string = file_get_contents('php://input'); ##今回のキモ
		$obj = json_decode($json_string);
		echo json_encode($obj);
		break;
	case 'put:user':
		$json_string = file_get_contents('php://input'); ##今回のキモ
		$obj = json_decode($json_string);
		echo json_encode($obj);
		break;
	case 'delete:user':
		echo "ユーザー #{$id} 削除";
		break;
*/
}