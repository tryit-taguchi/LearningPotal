<?PHP
/**
 * グラフ用の集計データ生成用クラス(静的クラス)
 *
 * 集計データ生成用クラス
 *
 * @access public
 * @author Atsushi Taguchi
 * @copyright Copyright (c) 2018, Tryit
 * @version 1.00
 * @since 2018/09/05
 */
/**
 */
class cAggregate
{
	/**
	 * 定数
	 */
	public $data;

	/**
	 * コンストラクタ
	 */
	function __construct() {
	}

	/**
	 * データ取得
	 * @param String $lectureName
	 * @param String $questionType
	 * @return void 無し
	 */
	static public function getData($lectureName) {
		$cond = array();
		$cond['LECTURE_NAME']  = $lectureName;
		$list = cMasterSite::getList($cond,"SITE_NO");
		return $list;
	}

	// 棒グラフ全体
	static public function aggregateBarTotal($questionType,$questionNo,$answerDate) {
		$db = new cDbExt();
		// 全体
		$sql = <<< SQL
		select QUESTION_NO,
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
		 where QUESTION_NO   = '{$questionNo}'
		   and ANSWER_DT     = '{$answerDate}'
		   and QUESTION_TYPE = '{$questionType}'
		 group by QUESTION_NO 
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
			$totalAnswerList[$rec['QUESTION_NO']] = $rec;
		}
		return $totalAnswerList;
	}

	// 棒グラフ会場
	static public function aggregateBarSite($questionType,$questionNo,$answerDate,$siteName,$lectureType) {
		$db = new cDbExt();
		// 会場
		$sql = <<< SQL
		select QUESTION_NO,
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
		 where T_ANSWER.QUESTION_NO   = '{$questionNo}'
		   and T_ANSWER.MEMBER_ID     = M_MEMBER.MEMBER_ID
		   and T_ANSWER.QUESTION_TYPE = '{$questionType}'
		   and T_ANSWER.ANSWER_DT     = '{$answerDate}'
	       and M_MEMBER.LECTURE_TYPE  = {$lectureType}
SQL;
		if( !empty($siteName) ) {
			$sql .= " and M_MEMBER.SITE_NAME     = '{$siteName}' ";
		}
		$sql .= " group by ANSWER_DT,QUESTION_NO ";
		//dout($sql);
		$list = $db->getList($sql);
		$siteAnswerList = array();
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
			$siteAnswerList = $rec;
		}
		return $siteAnswerList;
	}

	// レーダーチャート全体
	static public function aggregateRaderTotal($questionType,$answerDate) {
		$db = new cDbExt();
		// 全体
$sql = <<< SQL
		select QUESTION_NO,
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
		 where T_ANSWER.QUESTION_TYPE = '{$questionType}'
		 group by QUESTION_NO
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
			$totalAnswerList[$rec['QUESTION_NO']] = $rec;
		}
		return $totalAnswerList;
	}

	// レーダーチャート会場
	static public function aggregateRaderSite($questionType,$answerDate,$siteName,$lectureType) {
		$db = new cDbExt();
		// 会場
$sql = <<< SQL
		select QUESTION_NO,
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
		 where T_ANSWER.QUESTION_TYPE = '{$questionType}'
		   and T_ANSWER.MEMBER_ID     = M_MEMBER.MEMBER_ID
		   and T_ANSWER.ANSWER_DT     = '{$answerDate}'
SQL;
		if( !empty($siteName) ) {
			$sql .= " and M_MEMBER.SITE_NAME     = '{$siteName}' ";
		}
		$sql .= " group by ANSWER_DT,QUESTION_NO ";

		//dout($sql);
		$list = $db->getList($sql);
		$siteAnswerList = array();
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
			$siteAnswerList[$rec['QUESTION_NO']] = $rec;
		}
		return $siteAnswerList;
	}

	// レーダーチャート受講者
	static public function aggregateRaderMember($questionType,$answerDate,$siteName,$memberId,$lectureType) {
		$db = new cDbExt();
		// レーダーチャート受講者
$sql = <<< SQL
		select QUESTION_NO,
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
		 where T_ANSWER.QUESTION_TYPE = '{$questionType}'
		   and T_ANSWER.MEMBER_ID     = M_MEMBER.MEMBER_ID
		   and T_ANSWER.ANSWER_DT     = '{$answerDate}'
		   and M_MEMBER.MEMBER_ID     = {$memberId}
SQL;
		if( !empty($siteName) ) {
			$sql .= " and M_MEMBER.SITE_NAME     = '{$siteName}' ";
		}
		$sql .= " group by ANSWER_DT,QUESTION_NO ";

		//dout($sql);
		$list = $db->getList($sql);
		$memberAnswerList = array();
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
			$memberAnswerList[$rec['QUESTION_NO']] = $rec;
		}
		return $memberAnswerList;
	}
	/*
	// 棒グラフ設問
	static public function aggregateSenseQuestion($qlist,$answerType) {
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

	// 棒グラフのメンバー回答集計を出す
	static public function aggregateSenseMember($qIds,$answerType,$answerDate,$memberId) {
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
	static public function aggregateCheckQuestion($qlist,$answerType) {
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
	static public function aggregateCheckTotal($qIds,$answerType) {
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
	static public function aggregateCheckMember($qIds,$answerType,$answerDate,$memberId) {
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
	*/
}
?>


