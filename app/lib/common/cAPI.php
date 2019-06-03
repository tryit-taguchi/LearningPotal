<?PHP
/**
 * API用クラス(静的クラス)
 *
 * API用クラス
 *
 * @access public
 * @author Atsushi Taguchi
 * @copyright Copyright (c) 2018, Tryit
 * @version 1.00
 * @since 2018/09/05
 */
/**
  QUESTION_DIV 質問区分 qo=1問1択 qm=1問複数択 qc=比較設問 rp=レポート ex=テスト en=アンケート
 */
class cAPI
{
	/**
	 * コンストラクタ
	 */
	function __construct() {
	}

	/**
	 * サーバ状態取得
	 * @param String キー
	 * @return null
	 */
	static public function getServerInfo() {
		$lectureName = cConfig::getValue("研修名");
		// 初期コンフィグデータ
		$config = self::createConfigData();
		$config = self::fileMargeConfigData($config);
		// その他基本データ
		$data = array(
			'lectureName' => $lectureName,
			'imgLogo' => '/img/logo.png',
			'imgTitle' => '/img/title.png',
			'imgTopVisual' => '/img/top_visual.png',
			'config' => $config,
		);
		cRest::success($data);
	}

	/**
	 * Config取得
	 * @param 無し
	 * @return null
	 */
	static public function getConfig() {
		$config = self::createConfigData();
		$config = self::fileMargeConfigData($config);
		cRest::success($config);
	}
	// 初期データ作成
	static public function createConfigData() {
		$lectureName = cConfig::getValue("研修名");
		$cond = array();
		$cond['LECTURE_NAME']  = $lectureName;
		$atrList = cMasterQuestionAtr::getList($cond);
		$config = new stdClass();
		$config->statusHome = new stdClass();
		$config->statusHome->enableList = array();
		$questionTypeList = array();
		foreach($atrList as $atr) {
			$questionTypeList[$atr['QUESTION_TYPE']]['value'] = DEFAULT_OFF;
			$questionTypeList[$atr['QUESTION_TYPE']]['name'] = $atr['QUESTION_NAME'];
		}
		$questionTypeList['catchphrase']['value'] = DEFAULT_OFF;
		$questionTypeList['catchphrase']['name'] = 'キャッチフレーズ';
		$questionTypeList['faq']['value'] = DEFAULT_OFF;         // FAQ
		$questionTypeList['faq']['name']  = 'FAQ';
		$config->statusHome->enableList = $questionTypeList;
		return $config;
	}
	// ファイルデータとマージ
	static public function fileMargeConfigData($config) {
		$jsonFile = dirname(__FILE__)."/../../api/json/config.json";
		if( file_exists($jsonFile) ) {
			$json = json_decode(file_get_contents($jsonFile));
			foreach( $json->data->statusHome->enableList as $key => $rec ) {
				$config->statusHome->enableList[$key]['value'] = (int)$rec->value;
			}
		}

		// マージファイルの保存
		$timeStamp = time();
		$dateStr   = date('Y-m-d H:i:s',$timeStamp);
		$response = array(
			'status'     => 'success',
			'message'    => $message,
			'update'     => $dateStr,
			'updatestamp'=> $timeStamp,
			'data'       => $config,
		);
		$jsonData = json_encode($response);
		if( !file_put_contents($jsonFile,$jsonData) ) {
			fLog("ファイル書き出しに失敗しました");
		}
		return $config;
	}

	/**
	 * Configセット
	 * @param 無し
	 * @return null
	 */
	static public function postConfig() {
		global $REQUEST;
		$form = json_decode(htmlspecialchars_decode($REQUEST->post['form']));
		if( !empty($form) ) {
			$config = self::createConfigData();
			$config = self::formMargeConfigData($config,$form);
			cRest::success($config);
		} else {
			cRest::error($config);
		}
	}
	// Formデータとマージ
	static public function formMargeConfigData($config,$form) {
		$jsonFile = dirname(__FILE__)."/../../api/json/config.json";
		$enableList = (array)$form->enableList;
		foreach( $enableList as $key => $val ) {
			$config->statusHome->enableList[$key]['value'] = $val;
		}
		// マージファイルの保存
		$timeStamp = time();
		$dateStr   = date('Y-m-d H:i:s',$timeStamp);
		$response = array(
			'status'     => 'success',
			'message'    => $message,
			'update'     => $dateStr,
			'updatestamp'=> $timeStamp,
			'data'       => $config,
		);
		$jsonData = json_encode($response);
		if( !file_put_contents($jsonFile,$jsonData) ) {
			fLog("ファイル書き出しに失敗しました");
		}
		return $config;
	}

	/**
	 * ログイン状態セット
	 * @param String キー
	 * @return null
	 */
	static public function postLogin() {
		global $REQUEST;
		$seatCd = strtoupper($REQUEST->post['SEAT_CD']);
		if( empty($seatCd) ) {
			cRest::error(null);
		}
		$cond = array();
		$cond['SEAT_CD']      = $seatCd;
		if( $seatCd[0] != '#' ) { // Admin以外の場合
			$cond['LECTURE_DT']   = date('Y-m-d');
			$cond['LECTURE_NAME'] = cConfig::getValue("研修名");
		}
		$member = cMasterMember::getData($cond);
		if( empty($member) ) {
			cRest::error(null);
		}
		$member['UPD_DT'] = date("Y-m-d H:i:s"); // ログイン時間更新
		cMasterMember::setData($member);
		if( $seatCd[0] == '#' ) { // Adminの場合
			$member['ADMIN_FLG'] = true;
		} else {
			$member['ADMIN_FLG'] = false;
		}
		$memberId = $member['MEMBER_ID'];
		list($session,$firstFlg) = self::loadSession($memberId);
		$session['member'] = $member;
		$data = array();
		$data['member']  = $member;
		$data['session'] = $session;
		cRest::success($data);
		return;
	}
	/**
	 * 問題の取得（回答結果があれば回答結果も含める）
	 * @param questionType 質問タイプ
	 * @param memberId     メンバーID
	 * @param questionNo   問題番号
	 * @param aggregateFlg true=集計データを含める false=集計データを含めない
	 * @return null
	 */
	static public function getQuestion($questionType,$memberId,$questionNo,$aggregateFlg = false) {
		if( empty($questionType) ) {
			cRest::error(null,'タイプが不明です。');
		}
		if( empty($memberId) && ctype_digit($memberId) !== false ) {
			cRest::error(null,'受講者が不明です。');
		}
		if( empty($questionNo) ) {
			cRest::error(null,'番号が不明です。');
		}
		$lectureName = cConfig::getValue("研修名");
		$questionList = array();
		$cond = array();
		$cond['LECTURE_NAME']  = $lectureName;
		$cond['QUESTION_TYPE'] = $questionType; // 質問タイプ
		$cond['QUESTION_NO']   = $questionNo;   // 質問番号
		$qrs = cMasterQuestion::getData($cond);
		$atr = cMasterQuestionAtr::getData($cond);
		$data = array();
		$data['QUESTION_ID']  = $qrs['QUESTION_ID'];
		$data['QUESTION_STR'] = $qrs['QUESTION_STR'];
		$data['QUESTION_NO']  = $qrs['QUESTION_NO'];
		$data['QUESTION_CNT'] = $atr['QUESTION_CNT'];
		$data['ANSWER_CNT']   = $qrs['ANSWER_CNT'];
		for($no = 0;$no < $qrs['ANSWER_CNT'];$no++) {
			$data['answerList'][$no]  = $qrs['ANSWER_'.$no];
		}
		// T_ANSWER からデータを取得する
		$cond = array();
		$cond['LECTURE_NAME']  = $lectureName;
		$cond['QUESTION_TYPE'] = $questionType; // 質問タイプ
		$cond['QUESTION_NO']   = $questionNo;   // 質問番号
		$cond['MEMBER_ID']     = $memberId;     // 受講者ID
		$ans = cTransactionAnswer::getData($cond);
		// 質問区分 による処理分け
		switch($atr['QUESTION_DIV']) {
			case 'qo': // 1問1択
			case 'qc': // 比較設問
			case 'qm': // 1問複数択
				$data['selectedNo'] = null;
				$data['selectedNoList'] = array();
				if( !empty($ans) ) {
					for($no = 0;$no < $qrs['ANSWER_CNT'];$no++) {
						if( $ans['ANSWER_'.$no] == 1 ) {
							$data['selectedNo'] = $no;
							$data['selectedNoList'][] = $no;
						}
					}
				}
				// 集計データを含める場合は計算
				if( $aggregateFlg ) {
					$member = cMasterMember::getData(array('MEMBER_ID' => $memberId));
					$answerDate  = $ans['ANSWER_DT'];
					$siteName    = $member['SITE_NAME'];
					$lectureType = $member['LECTURE_TYPE'];
					// 会場全体
					$agtSite = cAggregate::aggregateBarSite($questionType,$questionNo,$answerDate,$siteName,$lectureType);
					$data['siteValueList'] = array();
					$data['siteSumList']   = array();
					for($no=0;$no<$qrs['ANSWER_CNT'];$no++) {
						$data['siteValueList'][] = $agtSite["PAR_ANSWER_{$no}"];
						$data['siteSumList'][]   = $agtSite["SUM_ANSWER_{$no}"];
					}
					// 全国平均
					$agtTotal = cAggregate::aggregateBarTotal($questionType,$questionNo,$answerDate);
					$data['totalValueList'] = array();
					$data['totalSumList']   = array();
					for($no=0;$no<$qrs['ANSWER_CNT'];$no++) {
						$data['totalValueList'][] = $agtSite["PAR_ANSWER_{$no}"];
						$data['totalSumList'][]   = $agtSite["SUM_ANSWER_{$no}"];
					}
				}
				$questionList[] = $data; // リストに加える
				break;
			case 'rp': // レポート
				$data['selectedNoList'] = array();
				for($no = 0;$no < $qrs['ANSWER_CNT'];$no++) {
					$data['selectedNoList'][] = 0;
				}
				if( !empty($ans) ) {
					for($no = 0;$no < $qrs['ANSWER_CNT'];$no++) {
						$data['selectedNoList'][] = $ans['ANSWER_'.$no];
					}
				}
				break;
			case 'ex': // テスト
			case 'en': // アンケート
				$data['selectedNo'] = null;
				$data['selectedNoList'] = array();
				if( !empty($ans) ) {
					for($no = 0;$no < $qrs['ANSWER_CNT'];$no++) {
						if( $ans['ANSWER_'.$no] == 1 ) {
							$data['selectedNo'] = $no;
							$data['selectedNoList'][] = $no;
						}
					}
				}
				break;
		}
		$ret = array();
		$ret['questionList'] = $questionList;
		cRest::success($ret);
	}
	/**
	 * タイプ内の問題の取得（回答結果があれば回答結果も含める）
	 * @param questionType 質問タイプ
	 * @param memberId     メンバーID
	 * @param questionNo   問題番号
	 * @param aggregateFlg true=集計データを含める false=集計データを含めない
	 * @return null
	 */
	static public function getQuestionList($questionType,$memberId,$aggregateFlg = false) {
		if( empty($questionType) ) {
			cRest::error(null,'タイプが不明です。');
		}
		if( empty($memberId) && ctype_digit($memberId) !== false ) {
			cRest::error(null,'受講者が不明です。');
		}

		$lectureName = cConfig::getValue("研修名");
		$questionList = array();

		$cond = array();
		$cond['LECTURE_NAME']  = $lectureName;
		$cond['QUESTION_TYPE'] = $questionType; // 質問タイプ
		$atr = cMasterQuestionAtr::getData($cond);
		for( $questionNo = 1; $questionNo <= $atr['QUESTION_CNT']; $questionNo++ ) {
			$cond = array();
			$cond['LECTURE_NAME']  = $lectureName;
			$cond['QUESTION_TYPE'] = $questionType; // 質問タイプ
			$cond['QUESTION_NO']   = $questionNo;   // 質問番号
			$qrs = cMasterQuestion::getData($cond);
			$data = array();
			$data['QUESTION_ID']  = $qrs['QUESTION_ID'];
			$data['QUESTION_STR'] = $qrs['QUESTION_STR'];
			$data['QUESTION_SHORT_STR'] = $qrs['QUESTION_SHORT_STR'];
			$data['QUESTION_NO']  = $qrs['QUESTION_NO'];
			$data['QUESTION_CNT'] = $atr['QUESTION_CNT'];
			$data['ANSWER_CNT']   = $qrs['ANSWER_CNT'];
			for($no = 0;$no < $qrs['ANSWER_CNT'];$no++) {
				$data['answerList'][$no]  = $qrs['ANSWER_'.$no];
			}
			for($no = 0;$no < $qrs['ANSWER_CNT'];$no++) {
				$data['answerShortList'][$no]  = $qrs['ANSWER_SHORT_'.$no];
			}
			// T_ANSWER からデータを取得する
			$cond = array();
			$cond['LECTURE_NAME']  = $lectureName;
			$cond['QUESTION_TYPE'] = $questionType; // 質問タイプ
			$cond['QUESTION_NO']   = $questionNo;   // 質問番号
			$cond['MEMBER_ID']     = $memberId;     // 受講者ID
			$ans = cTransactionAnswer::getData($cond);
			// 質問区分 による処理分け
			switch($atr['QUESTION_DIV']) {
				case 'qo': // 1問1択
				case 'qc': // 比較設問
				case 'qm': // 1問複数択
					$data['selectedNo'] = null;
					$data['selectedNoList'] = array();
					if( !empty($ans) ) {
						for($no = 0;$no < $qrs['ANSWER_CNT'];$no++) {
							if( $ans['ANSWER_'.$no] == 1 ) {
								$data['selectedNo'] = $no;
								$data['selectedNoList'][] = $no;
							}
						}
					}
					// 集計データを含める場合は計算
					if( $aggregateFlg ) {
						$member = cMasterMember::getData(array('MEMBER_ID' => $memberId));
						$answerDate  = $ans['ANSWER_DT'];
						$siteName    = $member['SITE_NAME'];
						$lectureType = $member['LECTURE_TYPE'];
						$agtSite = cAggregate::aggregateBarSite($questionType,$questionNo,$answerDate,$siteName,$lectureType);
						$data['siteValueList'] = array();
						$data['siteSumList']   = array();
						for($no=0;$no<$qrs['ANSWER_CNT'];$no++) {
							$data['siteValueList'][] = $agtSite["PAR_ANSWER_{$no}"];
							$data['siteSumList'][]   = $agtSite["SUM_ANSWER_{$no}"];
						}
						$agtTotal = cAggregate::aggregateBarTotal($questionType,$questionNo,$answerDate);
						$data['totalValueList'] = array();
						$data['totalSumList']   = array();
						for($no=0;$no<$qrs['ANSWER_CNT'];$no++) {
							$data['totalValueList'][] = $agtSite["PAR_ANSWER_{$no}"];
							$data['totalSumList'][]   = $agtSite["SUM_ANSWER_{$no}"];
						}
					}
					break;
				case 'rp': // レポート
					$data['selectedNoList'] = array();
					for($no = 0;$no < $qrs['ANSWER_CNT'];$no++) {
						$data['selectedNoList'][$no] = 0;
					}
					if( !empty($ans) ) {
						for($no = 0;$no < $qrs['ANSWER_CNT'];$no++) {
							$data['selectedNoList'][$no] = (int)$ans['ANSWER_'.$no];
						}
					}
					break;
				case 'ex': // テスト
				case 'en': // アンケート
					$data['selectedNo'] = null;
					$data['selectedNoList'] = array();
					if( !empty($ans) ) {
						for($no = 0;$no < $qrs['ANSWER_CNT'];$no++) {
							if( $ans['ANSWER_'.$no] == 1 ) {
								$data['selectedNo'] = $no;
								$data['selectedNoList'][] = $no;
							}
						}
					}
					break;
			}
			$questionList[] = $data; // リストに加える
		}
		$ret = array();
		$ret['questionList'] = $questionList;

		$chartList = array();
		switch($atr['QUESTION_DIV']) {
			case 'rp': // レポート
				// 集計データを含める場合は計算
				if( $aggregateFlg ) {
					$chartList['member']['questionStr'] = "あなた";
					$chartList['site']['questionStr'] = "会場全体";
					$chartList['total']['questionStr'] = "全国平均";
					$cond = array();
					$cond['LECTURE_NAME']  = $lectureName;
					$cond['QUESTION_TYPE'] = $questionType; // 質問タイプ
					$qrsList = cMasterQuestion::getList($cond,"QUESTION_NO");
					// 設問の大枠データ作成
					$answerList = array();
					$chartTypeList = array();
					$chartList['property']['valueMax'] = $atr['ANSWER_SELECT_CNT'];
					foreach($qrsList as $qrs) {
						$answerList[] = $qrs['QUESTION_SHORT_STR'];
						for( $ano=0; $ano<$qrs['ANSWER_CNT']; $ano++ ) {
							$chartTypeList[$ano] = $qrs["ANSWER_SHORT_{$ano}"];
						}
					}
					$chartList['member']['answerList'] = $answerList;
					$chartList['site']['answerList']   = $answerList;
					$chartList['total']['answerList']  = $answerList;
					
					$member = cMasterMember::getData(array('MEMBER_ID' => $memberId));
					$answerDate  = $ans['ANSWER_DT'];
					$siteName    = $member['SITE_NAME'];
					$lectureType = $member['LECTURE_TYPE'];

					// フィルカラー
					$backgroundColorList = array();
					$backgroundColorList[0] = 'rgba(255, 206, 86, 0.2)';
					$backgroundColorList[1] = 'rgba( 86, 206,255, 0.2)';
					$backgroundColorList[2] = 'rgba(200, 200,200, 0.2)';
					$backgroundColorList[3] = 'rgba(200,  86,200, 0.2)';
					$backgroundColorList[4] = 'rgba(200, 200, 86, 0.2)';
					$backgroundColorList[5] = 'rgba(200,  86,200, 0.2)';
					$backgroundColorList[6] = 'rgba( 86,  86,200, 0.2)';
					$backgroundColorList[7] = 'rgba( 86, 200, 86, 0.2)';
					$backgroundColorList[8] = 'rgba( 86,  86, 86, 0.2)';
					$backgroundColorList[9] = 'rgba( 86, 255,200, 0.2)';
					// ボーダーカラー
					$borderColorList = array();
					$borderColorList[0] = 'rgba(255, 206, 86, 1)';
					$borderColorList[1] = 'rgba( 86, 206,255, 1)';
					$borderColorList[2] = 'rgba(200, 200,200, 1)';
					$borderColorList[3] = 'rgba(200,  86,200, 1)';
					$borderColorList[4] = 'rgba(200, 200, 86, 1)';
					$borderColorList[5] = 'rgba(200,  86,200, 1)';
					$borderColorList[6] = 'rgba( 86,  86,200, 1)';
					$borderColorList[7] = 'rgba( 86, 200, 86, 1)';
					$borderColorList[8] = 'rgba( 86,  86, 86, 1)';
					$borderColorList[9] = 'rgba( 86, 200,200, 1)';

					// ネームリスト作成
					$valueNameList = array();
					for( $ano=0; $ano<$qrs['ANSWER_CNT']; $ano++ ) {
						$valueNameList[$ano]['valueName']   = $qrs["ANSWER_SHORT_{$ano}"];
						$valueNameList[$ano]['backgroundColor'] = $backgroundColorList[$ano];
						$valueNameList[$ano]['borderColor'] = $borderColorList[$ano];
					}
					// 受講者（あなた）
					$agtMember = cAggregate::aggregateRaderMember($questionType,$answerDate,$siteName,$memberId,$lectureType);
					$arr = $valueNameList;
					foreach( $agtMember as $agt ) {
						for( $ano=0; $ano<$qrs['ANSWER_CNT']; $ano++ ) {
							$arr[$ano]['valueList'][] = $agt["AVG_ANSWER_{$ano}"];
						}
					}
					$chartList['member']['aggregateList'] = $arr;
					// 会場全体
					$agtSite   = cAggregate::aggregateRaderSite($questionType,$answerDate,$siteName,$lectureType);
					$arr = $valueNameList;
					foreach( $agtSite as $agt ) {
						for( $ano=0; $ano<$qrs['ANSWER_CNT']; $ano++ ) {
							$arr[$ano]['valueList'][] = $agt["AVG_ANSWER_{$ano}"];
						}
					}
					$chartList['site']['aggregateList'] = $arr;
					// 全国平均
					$agtTotal  = cAggregate::aggregateRaderTotal($questionType,$answerDate);
					$arr = $valueNameList;
					foreach( $agtTotal as $agt ) {
						for( $ano=0; $ano<$qrs['ANSWER_CNT']; $ano++ ) {
							$arr[$ano]['valueList'][] = $agt["AVG_ANSWER_{$ano}"];
						}
					}
					$chartList['total']['aggregateList'] = $arr;
				}
				break;
		}
		$ret['chartList'] = $chartList;

		$cond = array();
		$cond['LECTURE_NAME']  = $lectureName;  // 講座名
		$cond['MEMBER_ID']     = $memberId;     // 受講者ID
		$cond['QUESTION_TYPE'] = $questionType; // 質問タイプ
		$comment = cTransactionComment::getData($cond);
		if( !empty($comment) ) {
			$ret['freeComment'] = $comment['COMMENT_STR'];
		}
		cRest::success($ret);
	}



	/**
	 * 問題の回答
	 * @param String キー
	 * @return null
	 */
	static public function postQuestion($questionType,$memberId) {
		// 処理はまだ
		$lectureName = cConfig::getValue("研修名");
		global $REQUEST;
		$nowDt = date('Y-m-d');
		$form = json_decode(htmlspecialchars_decode($REQUEST->post['form']));
		if( !empty($form) ) {
			// 回答の保存
			foreach($form->answerList as $answer) {
				$questionNo = $answer->QUESTION_NO;
				// フォームデータがあったら記録する
				// 問題データを取得する
				$cond = array();
				$cond['LECTURE_NAME']  = $lectureName;  // 講座名
				$cond['QUESTION_TYPE'] = $questionType; // 質問タイプ
				$cond['QUESTION_NO']   = $questionNo;   // 質問番号
				$qrs = cMasterQuestion::getData($cond);
				$atr = cMasterQuestionAtr::getData($cond);
				// T_ANSWER からデータを取得する
				$cond = array();
				$cond['LECTURE_NAME']  = $lectureName;  // 講座名
				$cond['QUESTION_TYPE'] = $questionType; // 質問タイプ
				$cond['QUESTION_NO']   = $questionNo;   // 質問番号
				$cond['MEMBER_ID']     = $memberId;     // 受講者ID
				$ans = cTransactionAnswer::getData($cond);
				$answerId = null;
				if( !empty($ans) ) {
					$answerId = $ans['ANSWER_ID'];
				}
				$data = array();
				$data['ANSWER_ID']     = $answerId;
				$data['ANSWER_DT']     = $nowDt;        // 登録日付
				$data['LECTURE_NAME']  = $lectureName;  // 講座名
				$data['QUESTION_TYPE'] = $questionType; // 質問タイプ
				$data['QUESTION_NO']   = $questionNo;   // 質問番号
				$data['MEMBER_ID']     = $memberId;     // 受講者ID
				// 質問区分 による処理分け
				switch($atr['QUESTION_DIV']) {
					case 'qo': // 1問1択
					case 'qc': // 比較設問
						for($no = 0;$no < $qrs['ANSWER_CNT'];$no++) {
							$val = 0;
							if( $answer->selectedNo == $no ) {
								$val = 1;
							}
							$data['ANSWER_'.$no] = $val;
						}
						break;
					case 'qm': // 1問複数択
						for($no = 0;$no < $qrs['ANSWER_CNT'];$no++) {
							$data['ANSWER_'.$no] = 0;
						}
						foreach($answer->selectedNoList as $no) {
							$data['ANSWER_'.$no] = 1;
						}
						break;
					case 'rp': // レポート
						foreach($answer->selectedNoList as $no => $val) {
							$data['ANSWER_'.$no] = $val;
						}
						break;
					case 'ex': // テスト
					case 'en': // アンケート
						for($no = 0;$no < $qrs['ANSWER_CNT'];$no++) {
							$val = 0;
							if( $answer->selectedNo == $no ) {
								$val = 1;
							}
							$data['ANSWER_'.$no] = $val;
						}
						break;
				}
				//fLog("回答 : " . $answer->selectedNo);
				cTransactionAnswer::setData($data);
			}
			// フリーコメントの保存
			if( !empty($form->freeComment) ) {
				$cond = array();
				$cond['LECTURE_NAME']  = $lectureName;  // 講座名
				$cond['COMMENT_DT']    = $nowDt;        // 登録日付
				$cond['MEMBER_ID']     = $memberId;     // 受講者ID
				$cond['QUESTION_TYPE'] = $questionType; // 質問タイプ
				$comment = cTransactionComment::getData($cond);
				$commentId = null;
				$data = array();
				if( !empty($comment) ) {
					$data['COMMENT_ID'] = $comment['COMMENT_ID'];
				}
				$data['LECTURE_NAME']  = $lectureName;  // 講座名
				$data['COMMENT_DT']    = $nowDt;        // 登録日付
				$data['MEMBER_ID']     = $memberId;     // 受講者ID
				$data['QUESTION_TYPE'] = $questionType; // 質問タイプ
				$data['COMMENT_STR']   = strim($form->freeComment);
				cTransactionComment::setData($data);
			}
		}
		// ポストと同時にセッションも保存する
		$data = array();
		$data['MEMBER_ID'] = $memberId;
		if( !empty($REQUEST->post['session']) ) {
			$data['SVAL'] = $REQUEST->post['session']; // json形式で渡ってくるからそのままDBに入れる
			cMasterSession::setData($data);
		}
		cRest::success($data);
	}


	/**
	 * セッションの取得
	 * @param Integer メンバーID
	 * @return null
	 */
	static public function getSession($memberId) {
		if( empty($memberId) ) {
			cRest::error(null);
		}
		$data = array();
		list($data,$firstFlg) = self::loadSession($memberId);
		cRest::success($data);
	}

	/**
	 * セッションロード
	 * @param Integer メンバーID
	 * @return null
	 */
	static private function loadSession($memberId) {
		$cond = array();
		$cond['MEMBER_ID'] = $memberId;
		$lectureName = cConfig::getValue("研修名");
		$firstFlg = false;
		$rec = cMasterSession::getData($cond);
		if( !empty($rec) ) {
			// セッションがあればデータ取得
			$firstFlg = false;
			//$sval = unserialize($rec['SVAL']);
			$session = json_decode($rec['SVAL']);
		}
		if( empty($session) ) {
			$firstFlg = true;
			$session = self::initSession();
			$cond = array();
			$cond['MEMBER_ID'] = $memberId;
			$member = cMasterMember::getData($cond);
			$session['member'] = $member;
		}
//fLog($session);
		return array($session,$firstFlg);
	}
	/**
	 * セッション初期化
	 * @param Integer メンバーID
	 * @param boolean true=初期化 false=ロード
	 * @return null
	 */
	static private function initSession() {
		$sval = array();
		$cond = array();
		$cond['LECTURE_NAME'] = cConfig::getValue("研修名");
		$atrList = cMasterQuestionAtr::getList($cond);
		$questionAtrList = array();
		foreach($atrList as $atr) {
			$questionAtr = array();
			$questionAtr['QUESTION_TYPE'] = $atr['QUESTION_TYPE'];
			$questionAtr['QUESTION_DIV']  = $atr['QUESTION_DIV'];
			$questionAtr['QUESTION_NAME'] = $atr['QUESTION_NAME'];
			$questionAtr['QUESTION_HTML'] = $atr['QUESTION_HTML'];
			$questionAtr['QUESTION_CNT']  = $atr['QUESTION_CNT'];
			$questionAtr['QUESTION_EXPLANATION']  = $atr['QUESTION_EXPLANATION'];
			$questionAtr['QUESTION_COMPLETE'] = false; // 
			$questionAtr['ANSWER_SELECT_CNT'] = (int)$atr['ANSWER_SELECT_CNT'];
			$questionAtr['currentQuestionNo'] = 1;
			$questionAtrList[$atr['QUESTION_TYPE']] = $questionAtr;
		}
		$sval['question_atr'] = $questionAtrList;
		return $sval;
	}

	/**
	 * セッションの書き込み
	 * @param Integer メンバーID
	 * @return null
	 */
	static public function postSession($memberId) {
		if( empty($memberId) ) {
			cRest::error(null);
		}
		global $REQUEST;
		$data = array();
		$data['MEMBER_ID'] = $memberId;
		//$data['SVAL'] = json_encode($REQUEST->post['session']);
		if( !empty($REQUEST->post['session']) ) {
			$data['SVAL'] = $REQUEST->post['session']; // json形式で渡ってくるからそのままDBに入れる
			cMasterSession::setData($data);
		}
		cRest::success($data);
	}

	/**
	 * FAQの取得
	 * @param questionType 質問タイプ
	 * @param memberId     メンバーID
	 * @param questionNo   問題番号
	 * @param aggregateFlg true=集計データを含める false=集計データを含めない
	 * @return null
	 */
	static public function getFaqList($faqType,$memberId) {
		$lectureName = cConfig::getValue("研修名");
		$catJson = cConfig::getValue("FAQカテゴリー");
		$catArr = json_decode($catJson);

		$faqList = array();
		$cond = array();
		$cond['LECTURE_NAME']  = $lectureName;
		foreach($catArr as $cat) {
			if( !empty($cat) ) {
				$faqList[$cat] = array();
			}
		}
		$list = cTransactionFaq::getList($cond,"FAQ_PID,FAQ_ID");
		foreach($list as $rec) {
			if( $rec['FAQ_TYPE'] == "主" ) {
				$faqList[$rec['FAQ_CAT']][$rec['FAQ_ID']] = $rec;
				$ref = &$rec;
			}
			if( $rec['FAQ_TYPE'] == "追加" ) {
				$faqList[$rec['FAQ_CAT']][$rec['FAQ_PID']]['ADD'][] = $rec;
			}
		}
		$faqCatList = array();
		// QAのあるカテゴリーを取得
		$cond = array();
		$cond['LECTURE_NAME']  = $lectureName;
		$cond['FAQ_TYPE']      = "主";
		$faqCatList = array();
		$list = cTransactionFaq::getList($cond,"FAQ_PID,FAQ_ID");
		foreach($list as $rec) {
			$faqCatList[$rec['FAQ_CAT']] = $rec['FAQ_CAT'];
		}
		$ret = array();
		$ret['faqCatList'] = $faqCatList;
		$ret['faqList'] = $faqList;
		cRest::success($ret);
	}

	/**
	 * キャッチフレーズセット
	 * @param 無し
	 * @return null
	 */
	static public function postCatchphraseInput($memberId) {
		if( empty($memberId) && ctype_digit($memberId) !== false ) {
			cRest::error(null,'受講者が不明です。');
		}
		global $REQUEST;

		$lectureName = cConfig::getValue("研修名");
		$nowDt = date('Y-m-d');
		$form = json_decode(htmlspecialchars_decode($REQUEST->post['form']));
		if( !empty($form) ) {
			$form->catchPhraseStr = strim($form->catchPhraseStr);
			if( !empty($form->catchPhraseStr) ) {
				$cond = array();
				$cond['LECTURE_NAME']  = $lectureName;  // 講座名
				$cond['COMMENT_DT']    = $nowDt;        // 登録日付
				$cond['MEMBER_ID']     = $memberId;     // 受講者ID
				$catchphrase = cTransactionCatchphrase::getData($cond);
				$data = array();
				if( !empty($catchphrase) ) {
					$data['CATCHPHRASE_ID'] = $catchphrase['CATCHPHRASE_ID'];
				}
				$data['LECTURE_NAME']    = $lectureName;  // 講座名
				$data['CATCHPHRASE_DT']  = $nowDt;        // 登録日付
				$data['MEMBER_ID']       = $memberId;     // 受講者ID
				$data['CATCHPHRASE_STR'] = $form->catchPhraseStr;
				cTransactionCatchphrase::setData($data);
			}
			$data = array();
			$data['catchphraseList'] = self::getCatchphraseList($memberId,$lectureName,$nowDt);
			cRest::success($data);
		} else {
			cRest::error($data);
		}
	}
	// 該当受講者に対応するのキャッチフレーズのリストを取り出す
	static public function getCatchphraseList($memberId,$lectureName,$lectureDate) {
		$member = cMasterMember::getData(array('MEMBER_ID' => $memberId));
		$siteName    = $member['SITE_NAME'];
		$lectureType = $member['LECTURE_TYPE'];
$sql = <<< SQL
select *
  from T_CATCHPHRASE,M_MEMBER
 where T_CATCHPHRASE.MEMBER_ID=M_MEMBER.MEMBER_ID
   and T_CATCHPHRASE.LECTURE_NAME='{$lectureName}'
   and T_CATCHPHRASE.CATCHPHRASE_DT='{$lectureDate}'
   and M_MEMBER.LECTURE_TYPE={$lectureType}
SQL;
		if( !empty($siteName) ) {
			$sql .= " and M_MEMBER.SITE_NAME     = '{$siteName}' ";
		}
		$sql .= " order by CATCHPHRASE_ID ";
		$db = new cDbExt();
		$catchphraseList = $db->getList($sql);
		return $catchphraseList;
	}

	/**
	 * キャッチフレーズ選択リスト取得
	 * @param 無し
	 * @return null
	 */
	static public function getCatchphraseSelect($memberId) {
		if( empty($memberId) && ctype_digit($memberId) !== false ) {
			cRest::error(null,'受講者が不明です。');
		}
		$lectureName = cConfig::getValue("研修名");
		$nowDt = date('Y-m-d');
		$data = array();
		$data['catchphraseList'] = self::getCatchphraseList($memberId,$lectureName,$nowDt);
		cRest::success($data);
	}

	/**
	 * キャッチフレーズ選択
	 * @param 無し
	 * @return null
	 */
	static public function postCatchphraseSelect($memberId) {
		if( empty($memberId) && ctype_digit($memberId) !== false ) {
			cRest::error(null,'受講者が不明です。');
		}
		global $REQUEST;

		$lectureName = cConfig::getValue("研修名");
		$nowDt = date('Y-m-d');
		$form = json_decode(htmlspecialchars_decode($REQUEST->post['form']));
		// 得票カウントアップ
		$db = new cDbExt();
		foreach( $form->checkedIdList as $cid ) {
			$cid = (int)$cid;
			$sql = "update T_CATCHPHRASE set VOTE_CNT=VOTE_CNT+1 where CATCHPHRASE_ID={$cid} ";
			$db->exec($sql);
		}
		$data = array();
		$data['catchphraseList'] = self::getCatchphraseList($memberId,$lectureName,$nowDt);
		cRest::success($data);
	}

	/**
	 * キャッチフレーズ結果取得
	 * @param 無し
	 * @return null
	 */
	static public function getCatchphraseResult($memberId) {
		if( empty($memberId) && ctype_digit($memberId) !== false ) {
			cRest::error(null,'受講者が不明です。');
		}
		$lectureName = cConfig::getValue("研修名");
		$member = cMasterMember::getData(array('MEMBER_ID' => $memberId));
		$siteName    = $member['SITE_NAME'];
		$lectureType = $member['LECTURE_TYPE'];
		$nowDt = date('Y-m-d');
		$db = new cDbExt();
		// 本日のランキング
$sql = <<< SQL
select M_MEMBER.MEMBER_ID,
       M_MEMBER.LECTURE_TYPE,
       T_CATCHPHRASE.VOTE_CNT,
       T_CATCHPHRASE.CATCHPHRASE_DT,
       T_CATCHPHRASE.CATCHPHRASE_STR
  from T_CATCHPHRASE,M_MEMBER
 where T_CATCHPHRASE.MEMBER_ID=M_MEMBER.MEMBER_ID
   and T_CATCHPHRASE.LECTURE_NAME='{$lectureName}'
   and T_CATCHPHRASE.CATCHPHRASE_DT='{$nowDt}'
   and M_MEMBER.LECTURE_TYPE={$lectureType}
 order by VOTE_CNT desc,CATCHPHRASE_ID
 limit 3
SQL;
		$catchphraseRankList = $db->getList($sql);

		// 過去のランキング
$sql = <<< SQL
select M_MEMBER.MEMBER_ID,
       M_SITE.SITE_ID,
       M_SITE.AREA_NAME,
       M_MEMBER.LECTURE_TYPE,
       T_CATCHPHRASE.VOTE_CNT,
       T_CATCHPHRASE.CATCHPHRASE_DT,
       T_CATCHPHRASE.CATCHPHRASE_STR
  from T_CATCHPHRASE,M_MEMBER,M_SITE
 where T_CATCHPHRASE.MEMBER_ID=M_MEMBER.MEMBER_ID
   and M_MEMBER.LECTURE_NAME = '{$lectureName}'
   and T_CATCHPHRASE.CATCHPHRASE_DT != '{$nowDt}'
   and M_SITE.LECTURE_NAME = M_MEMBER.LECTURE_NAME
   and M_SITE.SITE_NAME = M_MEMBER.SITE_NAME
  order by M_SITE.SITE_ID,CATCHPHRASE_DT desc,VOTE_CNT desc,M_MEMBER.LECTURE_TYPE
SQL;
		$list = $db->getList($sql);
		$catchphraseOldList = array();
		foreach($list as $rec) {
			if( !isset( $catchphraseOldList[$rec['AREA_NAME']][$rec['CATCHPHRASE_DT']][$rec['LECTURE_TYPE']] ) ) {
				$catchphraseOldList[$rec['AREA_NAME']][$rec['CATCHPHRASE_DT']][$rec['LECTURE_TYPE']] = $rec['CATCHPHRASE_STR'];
			}
		}

		$data = array();
		$data['catchphraseRankList'] = $catchphraseRankList;
		$data['catchphraseOldList'] = $catchphraseOldList;

		cRest::success($data);
	}

}
?>
