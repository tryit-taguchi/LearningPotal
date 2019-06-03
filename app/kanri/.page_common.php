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

function codeCnv($str) {
	$str = cNormalize::zen2HanUpper(strim($str));
	if( strlen($str) != 5 ) return "";
	return $str;
}

function dateCnv($str) {
	$str  = cNormalize::zen2Han($str);
	$str  = strim($str);
	if( empty($str) ) return "";
	$tmp1 = explode("(",$str);
	$md   = $tmp1[0];
	$tmp2 = explode("/",$md);
	$ymd   = $tmp2[0].'-'.$tmp2[1].'-'.$tmp2[2];
	return $ymd;
}

function typeCnv($zenhan,$kouhan) {
	$zenhan = strim($zenhan);
	if( $zenhan == '前半' ) return 1;
	$zenhan = strim($zenhan);
	if( $zenhan == '後半' ) return 2;
	$kouhan = strim($kouhan);
	if( $kouhan == '前半' ) return 1;
	$kouhan = strim($kouhan);
	if( $kouhan == '後半' ) return 2;
	return 0;
}

//dout($questionAtrList);
// url上のディレクトリ名を取得
function getUrlDir() {
	return dirname($_SERVER['REQUEST_URI']);
}

/*
 * 標準テンプレートの呼び出し
 * @param 無し
 * @return 無し
*/
function defaultTemplate($form) {
	global $lectureDateList;
	global $errorMessage;
	global $resultMessage;
	$tmp = explode("?",$_SERVER["REQUEST_URI"]);
	$bname = basename($tmp[0]);
	$tmp = explode(".",$bname);
	$bname = basename($tmp[0]);
	if( $bname == "kanri" || $bname == "nsp" ) {
		include("template/index.html");
	} else {
		include("template/{$bname}.html");
	}
}


// 棒グラフ設問
function aggregateSenseQuestion($qlist,$answerType) {
	// 該当の研修名のQUESTION_IDのみを対象とする
	$questionList = array();
	$qIdList = array();
	foreach($qlist as $rec) {
		$qidList[] = $rec['QUESTION_ID'];
		$questionList[$rec['QUESTION_ID']] = $rec;
	}
	$qIds = join(",",$qidList);
	return array($qIds,$questionList);
}
// 棒グラフ全体
function aggregateSenseTotal($qIds,$answerType) {
	$db = new cDbExt();
	// 全体
$sql = <<< SQL
	select QUESTION_ID,
	       sum(ANSWER_0) as SUM_ANSWER_0,
	       sum(ANSWER_1) as SUM_ANSWER_1,
	       sum(ANSWER_2) as SUM_ANSWER_2,
	       sum(ANSWER_3) as SUM_ANSWER_3,
	       sum(ANSWER_4) as SUM_ANSWER_4,
	       sum(ANSWER_5) as SUM_ANSWER_5,
	       sum(ANSWER_6) as SUM_ANSWER_6,
	       sum(ANSWER_7) as SUM_ANSWER_7,
	       sum(ANSWER_8) as SUM_ANSWER_8,
	       sum(ANSWER_9) as SUM_ANSWER_9,
	       ANSWER_DT
	  from T_ANSWER
	 where QUESTION_ID in ({$qIds})
	   and ANSWER_TYPE = {$answerType}
	 group by QUESTION_ID 
SQL;
	$list = $db->getList($sql);
	$totalAnswerList = array();
	foreach($list as $rec) {
		$rec['SUM_TOTAL'] = $rec['SUM_ANSWER_0']
		                  + $rec['SUM_ANSWER_1']
		                  + $rec['SUM_ANSWER_2']
		                  + $rec['SUM_ANSWER_3']
		                  + $rec['SUM_ANSWER_4']
		                  + $rec['SUM_ANSWER_5']
		                  + $rec['SUM_ANSWER_6']
		                  + $rec['SUM_ANSWER_7']
		                  + $rec['SUM_ANSWER_8']
		                  + $rec['SUM_ANSWER_9'];
		$rec['PAR_ANSWER_0'] = round($rec['SUM_ANSWER_0'] / $rec['SUM_TOTAL'] * 100, 0);
		$rec['PAR_ANSWER_1'] = round($rec['SUM_ANSWER_1'] / $rec['SUM_TOTAL'] * 100, 0);
		$rec['PAR_ANSWER_2'] = round($rec['SUM_ANSWER_2'] / $rec['SUM_TOTAL'] * 100, 0);
		$rec['PAR_ANSWER_3'] = round($rec['SUM_ANSWER_3'] / $rec['SUM_TOTAL'] * 100, 0);
		$rec['PAR_ANSWER_4'] = round($rec['SUM_ANSWER_4'] / $rec['SUM_TOTAL'] * 100, 0);
		$rec['PAR_ANSWER_5'] = round($rec['SUM_ANSWER_5'] / $rec['SUM_TOTAL'] * 100, 0);
		$rec['PAR_ANSWER_6'] = round($rec['SUM_ANSWER_6'] / $rec['SUM_TOTAL'] * 100, 0);
		$rec['PAR_ANSWER_7'] = round($rec['SUM_ANSWER_7'] / $rec['SUM_TOTAL'] * 100, 0);
		$rec['PAR_ANSWER_8'] = round($rec['SUM_ANSWER_8'] / $rec['SUM_TOTAL'] * 100, 0);
		$rec['PAR_ANSWER_9'] = round($rec['SUM_ANSWER_9'] / $rec['SUM_TOTAL'] * 100, 0);
		$totalAnswerList[$rec['QUESTION_ID']] = $rec;
	}
	return $totalAnswerList;
}
// 棒グラフ会場
function aggregateSenseGroup($qIds,$answerType,$answerDate,$siteName,$lectureType) {
	$db = new cDbExt();
	// 会場
$sql = <<< SQL
	select QUESTION_ID,
	       sum(ANSWER_0) as SUM_ANSWER_0,
	       sum(ANSWER_1) as SUM_ANSWER_1,
	       sum(ANSWER_2) as SUM_ANSWER_2,
	       sum(ANSWER_3) as SUM_ANSWER_3,
	       sum(ANSWER_4) as SUM_ANSWER_4,
	       sum(ANSWER_5) as SUM_ANSWER_5,
	       sum(ANSWER_6) as SUM_ANSWER_6,
	       sum(ANSWER_7) as SUM_ANSWER_7,
	       sum(ANSWER_8) as SUM_ANSWER_8,
	       sum(ANSWER_9) as SUM_ANSWER_9,
	       ANSWER_DT
	  from T_ANSWER,M_MEMBER
	 where T_ANSWER.QUESTION_ID in ({$qIds})
	   and T_ANSWER.MEMBER_ID=M_MEMBER.MEMBER_ID
	   and T_ANSWER.ANSWER_TYPE = {$answerType}
	   and T_ANSWER.ANSWER_DT   = '{$answerDate}'
	   and M_MEMBER.SITE_NAME   = '{$siteName}'
       and M_MEMBER.LECTURE_TYPE= {$lectureType}
	 group by ANSWER_DT,QUESTION_ID 
SQL;
	//dout($sql);
	$list = $db->getList($sql);
	$groupAnswerList = array();
	foreach($list as $rec) {
		$rec['SUM_TOTAL'] = $rec['SUM_ANSWER_0']
		                  + $rec['SUM_ANSWER_1']
		                  + $rec['SUM_ANSWER_2']
		                  + $rec['SUM_ANSWER_3']
		                  + $rec['SUM_ANSWER_4']
		                  + $rec['SUM_ANSWER_5']
		                  + $rec['SUM_ANSWER_6']
		                  + $rec['SUM_ANSWER_7']
		                  + $rec['SUM_ANSWER_8']
		                  + $rec['SUM_ANSWER_9'];
		$rec['PAR_ANSWER_0'] = round($rec['SUM_ANSWER_0'] / $rec['SUM_TOTAL'] * 100, 0);
		$rec['PAR_ANSWER_1'] = round($rec['SUM_ANSWER_1'] / $rec['SUM_TOTAL'] * 100, 0);
		$rec['PAR_ANSWER_2'] = round($rec['SUM_ANSWER_2'] / $rec['SUM_TOTAL'] * 100, 0);
		$rec['PAR_ANSWER_3'] = round($rec['SUM_ANSWER_3'] / $rec['SUM_TOTAL'] * 100, 0);
		$rec['PAR_ANSWER_4'] = round($rec['SUM_ANSWER_4'] / $rec['SUM_TOTAL'] * 100, 0);
		$rec['PAR_ANSWER_5'] = round($rec['SUM_ANSWER_5'] / $rec['SUM_TOTAL'] * 100, 0);
		$rec['PAR_ANSWER_6'] = round($rec['SUM_ANSWER_6'] / $rec['SUM_TOTAL'] * 100, 0);
		$rec['PAR_ANSWER_7'] = round($rec['SUM_ANSWER_7'] / $rec['SUM_TOTAL'] * 100, 0);
		$rec['PAR_ANSWER_8'] = round($rec['SUM_ANSWER_8'] / $rec['SUM_TOTAL'] * 100, 0);
		$rec['PAR_ANSWER_9'] = round($rec['SUM_ANSWER_9'] / $rec['SUM_TOTAL'] * 100, 0);
		$groupAnswerList[$rec['QUESTION_ID']] = $rec;
	}
	return $groupAnswerList;
}

// 棒グラフのメンバー回答集計を出す
function aggregateSenseMember($qIds,$answerType,$answerDate,$memberId) {
	$db = new cDbExt();
$sql = <<< SQL
	select QUESTION_ID,
	       ANSWER_0,
	       ANSWER_1,
	       ANSWER_2,
	       ANSWER_3,
	       ANSWER_4,
	       ANSWER_5,
	       ANSWER_6,
	       ANSWER_7,
	       ANSWER_8,
	       ANSWER_9,
	       ANSWER_DT
	  from T_ANSWER
	 where QUESTION_ID in ({$qIds})
	   and ANSWER_TYPE = {$answerType}
	   and ANSWER_DT   = '{$answerDate}'
	   and MEMBER_ID   = {$memberId}
SQL;
	//dout($sql);
	$list = $db->getList($sql);
	$memberAnswerList = array();
	foreach($list as $rec) {
		if( $rec['ANSWER_0'] == 1 ) $ano = 0;
		if( $rec['ANSWER_1'] == 1 ) $ano = 1;
		if( $rec['ANSWER_2'] == 1 ) $ano = 2;
		if( $rec['ANSWER_3'] == 1 ) $ano = 3;
		if( $rec['ANSWER_4'] == 1 ) $ano = 4;
		if( $rec['ANSWER_5'] == 1 ) $ano = 5;
		if( $rec['ANSWER_6'] == 1 ) $ano = 6;
		if( $rec['ANSWER_7'] == 1 ) $ano = 7;
		if( $rec['ANSWER_8'] == 1 ) $ano = 8;
		if( $rec['ANSWER_9'] == 1 ) $ano = 9;
		$rec['ANSWER_NO'] = $ano;
		$memberAnswerList[$rec['QUESTION_ID']] = $rec;
	}
	// 結果を返す
	return $memberAnswerList;
}

// レーダーチャート設問
function aggregateCheckQuestion($qlist,$answerType) {
	$questionList = array();
	$qIdList = array();
	foreach($qlist as $rec) {
		$qidList[] = $rec['QUESTION_ID'];
		$questionList[$rec['QUESTION_ID']] = $rec;
	}
	$qIds = join(",",$qidList);
	return array($qIds,$questionList);
}
// レーダーチャート全体
function aggregateCheckTotal($qIds,$answerType) {
	$db = new cDbExt();
	// 全体
$sql = <<< SQL
	select QUESTION_ID,
	       avg(ANSWER_0) as AVG_ANSWER_0,
	       avg(ANSWER_1) as AVG_ANSWER_1,
	       avg(ANSWER_2) as AVG_ANSWER_2,
	       avg(ANSWER_3) as AVG_ANSWER_3,
	       avg(ANSWER_4) as AVG_ANSWER_4,
	       avg(ANSWER_5) as AVG_ANSWER_5,
	       avg(ANSWER_6) as AVG_ANSWER_6,
	       avg(ANSWER_7) as AVG_ANSWER_7,
	       avg(ANSWER_8) as AVG_ANSWER_8,
	       avg(ANSWER_9) as AVG_ANSWER_9,
	       ANSWER_DT
	  from T_ANSWER
	 where QUESTION_ID in ({$qIds})
	   and ANSWER_TYPE = {$answerType}
	 group by QUESTION_ID 
SQL;
	//dout($sql);
	$list = $db->getList($sql);
	$totalAnswerList = array();
	foreach($list as $rec) {
		$rec['AVG_TOTAL'] = $rec['AVG_ANSWER_0']
		                  + $rec['AVG_ANSWER_1']
		                  + $rec['AVG_ANSWER_2']
		                  + $rec['AVG_ANSWER_3']
		                  + $rec['AVG_ANSWER_4']
		                  + $rec['AVG_ANSWER_5']
		                  + $rec['AVG_ANSWER_6']
		                  + $rec['AVG_ANSWER_7']
		                  + $rec['AVG_ANSWER_8']
		                  + $rec['AVG_ANSWER_9'];
		$rec['PAR_ANSWER_0'] = round($rec['AVG_ANSWER_0'] / $rec['AVG_TOTAL'] * 100, 0);
		$rec['PAR_ANSWER_1'] = round($rec['AVG_ANSWER_1'] / $rec['AVG_TOTAL'] * 100, 0);
		$rec['PAR_ANSWER_2'] = round($rec['AVG_ANSWER_2'] / $rec['AVG_TOTAL'] * 100, 0);
		$rec['PAR_ANSWER_3'] = round($rec['AVG_ANSWER_3'] / $rec['AVG_TOTAL'] * 100, 0);
		$rec['PAR_ANSWER_4'] = round($rec['AVG_ANSWER_4'] / $rec['AVG_TOTAL'] * 100, 0);
		$rec['PAR_ANSWER_5'] = round($rec['AVG_ANSWER_5'] / $rec['AVG_TOTAL'] * 100, 0);
		$rec['PAR_ANSWER_6'] = round($rec['AVG_ANSWER_6'] / $rec['AVG_TOTAL'] * 100, 0);
		$rec['PAR_ANSWER_7'] = round($rec['AVG_ANSWER_7'] / $rec['AVG_TOTAL'] * 100, 0);
		$rec['PAR_ANSWER_8'] = round($rec['AVG_ANSWER_8'] / $rec['AVG_TOTAL'] * 100, 0);
		$rec['PAR_ANSWER_9'] = round($rec['AVG_ANSWER_9'] / $rec['AVG_TOTAL'] * 100, 0);
		$totalAnswerList[$rec['QUESTION_ID']] = $rec;
	}
	return $totalAnswerList;
}

// レーダーチャート会場
function aggregateCheckGroup($qIds,$answerType,$answerDate,$siteName) {
	$db = new cDbExt();
	// 会場
$sql = <<< SQL
	select QUESTION_ID,
	       avg(ANSWER_0) as AVG_ANSWER_0,
	       avg(ANSWER_1) as AVG_ANSWER_1,
	       avg(ANSWER_2) as AVG_ANSWER_2,
	       avg(ANSWER_3) as AVG_ANSWER_3,
	       avg(ANSWER_4) as AVG_ANSWER_4,
	       avg(ANSWER_5) as AVG_ANSWER_5,
	       avg(ANSWER_6) as AVG_ANSWER_6,
	       avg(ANSWER_7) as AVG_ANSWER_7,
	       avg(ANSWER_8) as AVG_ANSWER_8,
	       avg(ANSWER_9) as AVG_ANSWER_9,
	       ANSWER_DT
	  from T_ANSWER,M_MEMBER
	 where T_ANSWER.QUESTION_ID in ({$qIds})
	   and T_ANSWER.MEMBER_ID=M_MEMBER.MEMBER_ID
	   and T_ANSWER.ANSWER_TYPE = {$answerType}
	   and T_ANSWER.ANSWER_DT   = '{$answerDate}'
	   and M_MEMBER.SITE_NAME   = '{$siteName}'
	 group by ANSWER_DT,QUESTION_ID 
SQL;
	//dout($sql);
	$list = $db->getList($sql);
	$groupAnswerList = array();
	foreach($list as $rec) {
		$rec['AVG_TOTAL'] = $rec['AVG_ANSWER_0']
		                  + $rec['AVG_ANSWER_1']
		                  + $rec['AVG_ANSWER_2']
		                  + $rec['AVG_ANSWER_3']
		                  + $rec['AVG_ANSWER_4']
		                  + $rec['AVG_ANSWER_5']
		                  + $rec['AVG_ANSWER_6']
		                  + $rec['AVG_ANSWER_7']
		                  + $rec['AVG_ANSWER_8']
		                  + $rec['AVG_ANSWER_9'];
		$rec['PAR_ANSWER_0'] = round($rec['AVG_ANSWER_0'] / $rec['AVG_TOTAL'] * 100, 0);
		$rec['PAR_ANSWER_1'] = round($rec['AVG_ANSWER_1'] / $rec['AVG_TOTAL'] * 100, 0);
		$rec['PAR_ANSWER_2'] = round($rec['AVG_ANSWER_2'] / $rec['AVG_TOTAL'] * 100, 0);
		$rec['PAR_ANSWER_3'] = round($rec['AVG_ANSWER_3'] / $rec['AVG_TOTAL'] * 100, 0);
		$rec['PAR_ANSWER_4'] = round($rec['AVG_ANSWER_4'] / $rec['AVG_TOTAL'] * 100, 0);
		$rec['PAR_ANSWER_5'] = round($rec['AVG_ANSWER_5'] / $rec['AVG_TOTAL'] * 100, 0);
		$rec['PAR_ANSWER_6'] = round($rec['AVG_ANSWER_6'] / $rec['AVG_TOTAL'] * 100, 0);
		$rec['PAR_ANSWER_7'] = round($rec['AVG_ANSWER_7'] / $rec['AVG_TOTAL'] * 100, 0);
		$rec['PAR_ANSWER_8'] = round($rec['AVG_ANSWER_8'] / $rec['AVG_TOTAL'] * 100, 0);
		$rec['PAR_ANSWER_9'] = round($rec['AVG_ANSWER_9'] / $rec['AVG_TOTAL'] * 100, 0);
		$groupAnswerList[$rec['QUESTION_ID']] = $rec;
	}
	return $groupAnswerList;
}


// レーダーチャートのメンバー回答集計を出す
function aggregateCheckMember($qIds,$answerType,$answerDate,$memberId) {
	$db = new cDbExt();
$sql = <<< SQL
	select QUESTION_ID,
	       ANSWER_0,
	       ANSWER_1,
	       ANSWER_2,
	       ANSWER_3,
	       ANSWER_4,
	       ANSWER_5,
	       ANSWER_6,
	       ANSWER_7,
	       ANSWER_8,
	       ANSWER_9,
	       ANSWER_DT
	  from T_ANSWER
	 where QUESTION_ID in ({$qIds})
	   and ANSWER_TYPE = {$answerType}
	   and ANSWER_DT   = '{$answerDate}'
	   and MEMBER_ID   = {$memberId}
SQL;
	$list = $db->getList($sql);
	$memberAnswerList = array();
	foreach($list as $rec) {
		if( $rec['ANSWER_0'] == 1 ) $ano = 0;
		if( $rec['ANSWER_1'] == 1 ) $ano = 1;
		if( $rec['ANSWER_2'] == 1 ) $ano = 2;
		if( $rec['ANSWER_3'] == 1 ) $ano = 3;
		if( $rec['ANSWER_4'] == 1 ) $ano = 4;
		if( $rec['ANSWER_5'] == 1 ) $ano = 5;
		if( $rec['ANSWER_6'] == 1 ) $ano = 6;
		if( $rec['ANSWER_7'] == 1 ) $ano = 7;
		if( $rec['ANSWER_8'] == 1 ) $ano = 8;
		if( $rec['ANSWER_9'] == 1 ) $ano = 9;
		//$rec['ANSWER_NO'] = $ano;
		$rec['ANSWER_NO'] = '';
		$memberAnswerList[$rec['QUESTION_ID']] = $rec;
	}
	// 結果を返す
	return $memberAnswerList;
}

?>