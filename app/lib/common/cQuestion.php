<?PHP
/**
 * 設問用クラス(静的クラス)
 *
 * 設問用クラス
 *
 * @access public
 * @author Atsushi Taguchi
 * @copyright Copyright (c) 2018, Tryit
 * @version 1.00
 * @since 2018/09/05
 */
/**
 */
class cQuestion
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
	 * データ保存
	 * @param String キー
	 * @param String 値
	 * @return void 無し
	 */
	static public function setExcelData($lectureName,$questionType,$questionName,$questionDiv,$line) {
		//$db = new cDbExt();
		if( $line == "" ) return false;
		$no = cNormalize::zen2HanUpper(strim($line['A']));
		$data = array();
		$data['QUESTION_TYPE']    = $questionType; // 設問タイプ
		$data['QUESTION_NO']      = cNormalize::zen2HanUpper(strim($line['A']));
		$data['QUESTION_STR']     = strim($line['B']);
		$data['QUESTION_SHORT_STR'] = strim($line['C']);
		$data['DEL_FLG']          = 0;
		$ansCnt = 0;
		if( strim($line['D']) != "" ) {
			$data['ANSWER_0']   = strim($line['D']); $ansCnt++;
		} else {
			$data['ANSWER_0']   = '';
		}
		if( strim($line['E']) != "" ) {
			$data['ANSWER_1']   = strim($line['E']); $ansCnt++;
		} else {
			$data['ANSWER_1']   = '';
		}
		if( strim($line['F']) != "" ) {
			$data['ANSWER_2']   = strim($line['F']); $ansCnt++;
		} else {
			$data['ANSWER_2']   = '';
		}
		if( strim($line['G']) != "" ) {
			$data['ANSWER_3']   = strim($line['G']); $ansCnt++;
		} else {
			$data['ANSWER_3']   = '';
		}
		if( strim($line['H']) != "" ) {
			$data['ANSWER_4']   = strim($line['H']); $ansCnt++;
		} else {
			$data['ANSWER_4']   = '';
		}
		if( strim($line['I']) != "" ) {
			$data['ANSWER_5']   = strim($line['I']); $ansCnt++;
		} else {
			$data['ANSWER_5']   = '';
		}
		if( strim($line['J']) != "" ) {
			$data['ANSWER_6']   = strim($line['J']); $ansCnt++;
		} else {
			$data['ANSWER_6']   = '';
		}
		if( strim($line['K']) != "" ) {
			$data['ANSWER_7']   = strim($line['K']); $ansCnt++;
		} else {
			$data['ANSWER_7']   = '';
		}
		if( strim($line['L']) != "" ) {
			$data['ANSWER_8']   = strim($line['L']); $ansCnt++;
		} else {
			$data['ANSWER_8']   = '';
		}
		if( strim($line['M']) != "" ) {
			$data['ANSWER_9']   = strim($line['M']); $ansCnt++;
		} else {
			$data['ANSWER_9']   = '';
		}
		$data['ANSWER_CNT']     = $ansCnt;

		$ansShortCnt = 0;
		if( strim($line['D']) != "" ) {
			$data['ANSWER_SHORT_0']   = strim($line['N']); $ansShortCnt++;
		} else {
			$data['ANSWER_SHORT_0']   = '';
		}
		if( strim($line['E']) != "" ) {
			$data['ANSWER_SHORT_1']   = strim($line['O']); $ansShortCnt++;
		} else {
			$data['ANSWER_SHORT_1']   = '';
		}
		if( strim($line['F']) != "" ) {
			$data['ANSWER_SHORT_2']   = strim($line['P']); $ansShortCnt++;
		} else {
			$data['ANSWER_SHORT_2']   = '';
		}
		if( strim($line['G']) != "" ) {
			$data['ANSWER_SHORT_3']   = strim($line['Q']); $ansShortCnt++;
		} else {
			$data['ANSWER_SHORT_3']   = '';
		}
		if( strim($line['H']) != "" ) {
			$data['ANSWER_SHORT_4']   = strim($line['R']); $ansShortCnt++;
		} else {
			$data['ANSWER_SHORT_4']   = '';
		}
		if( strim($line['I']) != "" ) {
			$data['ANSWER_SHORT_5']   = strim($line['S']); $ansShortCnt++;
		} else {
			$data['ANSWER_SHORT_5']   = '';
		}
		if( strim($line['J']) != "" ) {
			$data['ANSWER_SHORT_6']   = strim($line['T']); $ansShortCnt++;
		} else {
			$data['ANSWER_SHORT_6']   = '';
		}
		if( strim($line['K']) != "" ) {
			$data['ANSWER_SHORT_7']   = strim($line['U']); $ansShortCnt++;
		} else {
			$data['ANSWER_SHORT_7']   = '';
		}
		if( strim($line['L']) != "" ) {
			$data['ANSWER_SHORT_8']   = strim($line['V']); $ansShortCnt++;
		} else {
			$data['ANSWER_SHORT_8']   = '';
		}
		if( strim($line['M']) != "" ) {
			$data['ANSWER_SHORT_9']   = strim($line['W']); $ansShortCnt++;
		} else {
			$data['ANSWER_SHORT_9']   = '';
		}
		if( !empty($line['X']) ) {
			$data['QUESTION_RID']   = strim($line['X']);
		}
		if( $ansCnt != $ansShortCnt ) {
			return 'discordance';
		}
		$data['LECTURE_NAME']   = $lectureName;
		// 必須項目が入っていないデータは除く（端末・日付・利用者名）
		if( $data['QUESTION_NO'] == '' 
		 || $data['QUESTION_STR'] == '' 
		 || $data['ANSWER_0'] == '' ) {
			return 'empty';
		}
		// レコードに（研修名・質問タイプ・質問番号）が既に割り当てられているかチェックし、割り当てられていたら上書きする
		$id = cMasterQuestion::setData($data);
		return $id;
	}

	/**
	 * インポート
	 * @param String $lectureName
	 * @param String $questionType
	 * @param Array  $files
	 * @return void 無し
	 */
	static public function importData($lectureName,$questionType,$files) {
		$cond = array();
		$cond['LECTURE_NAME']  = $lectureName;
		$cond['QUESTION_TYPE'] = $questionType; // 質問タイプ
		$atr  = cMasterQuestionAtr::getData($cond);
		$questionName = $atr['QUESTION_NAME'];
		$questionDiv  = $atr['QUESTION_DIV'];
		if($files['file']["tmp_name"]){
			$result = new StdClass();
			// アップロードされたファイルの取得
			$file_type = pathinfo($files['file']['name'],PATHINFO_EXTENSION);
			if( $file_type != "xlsx" ) {
				$result->status = "error";
				$result->result = new StdClass();
				$result->result->errorMessage = "EXCEL以外のファイルが指定されています。";
				fLog($result->result->errorMessage);
				echo json_encode($result);
				exit;
			}
			$file_path = sys_get_temp_dir().'/'.$files['file']['name'];
			if( move_uploaded_file($files['file']['tmp_name'],$file_path) ) {
				//正常
				fLog("インポートファイル正常");
				fLog("[" . $file_path ."]");
			}else{
				//コピーに失敗（だいたい、ディレクトリがないか、パーミッションエラー）
				$result->status = "error";
				$result->result = new StdClass();
				$result->result->errorMessage = "失敗：ディレクトリがないか、パーミッションエラー。\n" . $file_path;
				fLog($result->result->errorMessage);
				echo json_encode($result);
				exit;
			}

			cExcel::load($file_path);
			$srcArr = cExcel::toArray();
			// ファイルチェック
			if( $srcArr[1]['A'] != $questionName ) {
				$result->status = "error";
				$result->result = new StdClass();
				$result->result->errorMessage = "{$questionName}ではないEXCELファイルです。";
				fLog($result->result->errorMessage);
				echo json_encode($result);
				exit;
			}
			// 一旦questionType一式に削除フラグを立てる
			$cond = array();
			$cond['LECTURE_NAME']  = $lectureName;
			$cond['QUESTION_TYPE'] = $questionType; // 質問タイプ
			cMasterQuestion::logDelQuestionType($cond);

			// DBに収納する
			$row = 0;
			$cnt = 0;
			foreach($srcArr as $line) {
				$row++;
				if( $row == 1 ) continue; // ヘッダは無視する
				if( $row == 2 ) {
					$questionExplanation = strim($line['B']);
					continue; // 説明文のみ入れる
				}
				if( $row == 3 ) continue; // ヘッダは無視する
				$ret = self::setExcelData($lectureName,$questionType,$questionName,$questionDiv,$line); // 保存する
				if( is_numeric($ret) ) {
					$cnt++;
				}
			}

			$atr = array();
			$atr['LECTURE_NAME']         = $lectureName;
			$atr['QUESTION_TYPE']        = $questionType; // 質問属性
			$atr['QUESTION_EXPLANATION'] = $questionExplanation; // 説明
			$atr['QUESTION_CNT']         = $cnt; // 質問数
			cMasterQuestionAtr::setData($atr);

			$result = new StdClass();
			$result->status = "success";
			$result->result = new StdClass();
			$result->result->lastUpdate = cDate::now();
			echo json_encode($result);
			exit;
		}
	}

	/**
	 * エクスポート
	 * @param String $lectureName
	 * @param String $questionType
	 * @return void 無し
	 */
	static public function exportData($lectureName,$questionType) {
		$cond = array();
		$cond['LECTURE_NAME']  = $lectureName;
		$cond['QUESTION_TYPE'] = $questionType; // 質問タイプ
		$atr  = cMasterQuestionAtr::getData($cond);
		$questionName = $atr['QUESTION_NAME'];

		$data  = self::getData($lectureName,$questionType);
		$atr  = $data['atr'];
		$list = $data['list'];
		cExcel::load("template/excel/question.xlsx");
		cExcel::cell( 1, 1, $questionName);
		cExcel::cell( 2, 2,$atr['QUESTION_EXPLANATION']);
		$no = 4;
		foreach($list as $rec) {
			cExcel::cell( 1, $no,$rec['QUESTION_NO']);
			cExcel::cell( 2, $no,$rec['QUESTION_STR']);
			cExcel::cell( 3, $no,$rec['QUESTION_SHORT_STR']);
			cExcel::cell( 4, $no,$rec['ANSWER_0']);
			cExcel::cell( 5, $no,$rec['ANSWER_1']);
			cExcel::cell( 6, $no,$rec['ANSWER_2']);
			cExcel::cell( 7, $no,$rec['ANSWER_3']);
			cExcel::cell( 8, $no,$rec['ANSWER_4']);
			cExcel::cell( 9, $no,$rec['ANSWER_5']);
			cExcel::cell(10, $no,$rec['ANSWER_6']);
			cExcel::cell(11, $no,$rec['ANSWER_7']);
			cExcel::cell(12, $no,$rec['ANSWER_8']);
			cExcel::cell(13, $no,$rec['ANSWER_9']);
			cExcel::cell(14, $no,$rec['ANSWER_SHORT_0']);
			cExcel::cell(15, $no,$rec['ANSWER_SHORT_1']);
			cExcel::cell(16, $no,$rec['ANSWER_SHORT_2']);
			cExcel::cell(17, $no,$rec['ANSWER_SHORT_3']);
			cExcel::cell(18, $no,$rec['ANSWER_SHORT_4']);
			cExcel::cell(19, $no,$rec['ANSWER_SHORT_5']);
			cExcel::cell(20, $no,$rec['ANSWER_SHORT_6']);
			cExcel::cell(21, $no,$rec['ANSWER_SHORT_7']);
			cExcel::cell(22, $no,$rec['ANSWER_SHORT_8']);
			cExcel::cell(23, $no,$rec['ANSWER_SHORT_9']);
			if( !empty($rec['QUESTION_RID']) ) {
				cExcel::cell(24, $no,$rec['QUESTION_RID']);
			}
			$no++;
		}
		$tmpDir = sys_get_temp_dir();
		$exceiFileName = "設問_{$questionName}_".date("YmdHis").".xlsx";
		cExcel::save($tmpDir."/{$exceiFileName}");
		// ダウンロードさせる
		cUtility::download($tmpDir."/{$exceiFileName}",$exceiFileName);
	}


	/**
	 * データ取得
	 * @param String $lectureName
	 * @param String $questionType
	 * @return void 無し
	 */
	static public function getData($lectureName,$questionType) {
		$cond = array();
		$cond['LECTURE_NAME']  = $lectureName;
		$cond['QUESTION_TYPE'] = $questionType; // 質問タイプ
		$atr  = cMasterQuestionAtr::getData($cond);
		$tmpList = cMasterQuestion::getList($cond,"QUESTION_NO");
		$list = array();
		foreach($tmpList as $rec) {
			$list[$rec['QUESTION_NO']] = $rec;
		}
		$ret  = array();
		$ret['atr']  = $atr;
		$ret['list'] = $list;
		return $ret;
	}

	/**
	 * 保存
	 * @param String $lectureName
	 * @param String $questionType
	 * @return void 無し
	 */
	static public function saveData($lectureName,$questionType,$form) {
		$cnt = 0;
		$cond['LECTURE_NAME']  = $lectureName;
		$cond['QUESTION_TYPE'] = $questionType; // 質問タイプ
		$atr  = cMasterQuestionAtr::getData($cond);
		$no = 1;
		foreach($form['q'] as $rec) {
			if( $atr['QUESTION_DIV'] == 'qc' ) {
				// 比較対象問題の場合は、比較対象からデータを持ってきてコピーする
				$cond = array();
				$cond['LECTURE_NAME']  = $lectureName;
				$cond['QUESTION_TYPE'] = $rec['R_QUESTION_TYPE'];
				$cond['QUESTION_NO']   = $rec['R_QUESTION_NO'];
				$rQes = cMasterQuestion::getData($cond);
				$setFlg = false;
				if( !empty($rQes) ) {
					if( $rec['R_QUESTION_TYPE'] != '' ) {
						if( $rec['R_QUESTION_NO'] >= 0 ) {
							$setFlg = true;
						}
					}
				}
				if( $setFlg == true ) {
					$rec['QUESTION_RID']    = $rQes['QUESTION_ID'];
					$rec['QUESTION_STR']    = $rQes['QUESTION_STR'];
					$rec['QUESTION_SHORT_STR'] = $rQes['QUESTION_SHORT_STR'];
					$rec['ANSWER_CNT']      = $rQes['ANSWER_CNT'];
					$rec['ANSWER_0']        = $rQes['ANSWER_0'];
					$rec['ANSWER_1']        = $rQes['ANSWER_1'];
					$rec['ANSWER_2']        = $rQes['ANSWER_2'];
					$rec['ANSWER_3']        = $rQes['ANSWER_3'];
					$rec['ANSWER_4']        = $rQes['ANSWER_4'];
					$rec['ANSWER_5']        = $rQes['ANSWER_5'];
					$rec['ANSWER_6']        = $rQes['ANSWER_6'];
					$rec['ANSWER_7']        = $rQes['ANSWER_7'];
					$rec['ANSWER_8']        = $rQes['ANSWER_8'];
					$rec['ANSWER_9']        = $rQes['ANSWER_9'];
					$rec['ANSWER_SHORT_0']  = $rQes['ANSWER_SHORT_0'];
					$rec['ANSWER_SHORT_1']  = $rQes['ANSWER_SHORT_1'];
					$rec['ANSWER_SHORT_2']  = $rQes['ANSWER_SHORT_2'];
					$rec['ANSWER_SHORT_3']  = $rQes['ANSWER_SHORT_3'];
					$rec['ANSWER_SHORT_4']  = $rQes['ANSWER_SHORT_4'];
					$rec['ANSWER_SHORT_5']  = $rQes['ANSWER_SHORT_5'];
					$rec['ANSWER_SHORT_6']  = $rQes['ANSWER_SHORT_6'];
					$rec['ANSWER_SHORT_7']  = $rQes['ANSWER_SHORT_7'];
					$rec['ANSWER_SHORT_8']  = $rQes['ANSWER_SHORT_8'];
					$rec['ANSWER_SHORT_9']  = $rQes['ANSWER_SHORT_9'];
				} else {
					$rec['QUESTION_STR'] = '';
				}
			}
			if( !empty($rec['QUESTION_STR']) ) {
				$ans = array();
				$ansCnt = 0;
				if( !empty($rec['ANSWER_0']) ) { $ans["ANSWER_{$ansCnt}"] = $rec['ANSWER_0']; $ansCnt++; }
				if( !empty($rec['ANSWER_1']) ) { $ans["ANSWER_{$ansCnt}"] = $rec['ANSWER_1']; $ansCnt++; }
				if( !empty($rec['ANSWER_2']) ) { $ans["ANSWER_{$ansCnt}"] = $rec['ANSWER_2']; $ansCnt++; }
				if( !empty($rec['ANSWER_3']) ) { $ans["ANSWER_{$ansCnt}"] = $rec['ANSWER_3']; $ansCnt++; }
				if( !empty($rec['ANSWER_4']) ) { $ans["ANSWER_{$ansCnt}"] = $rec['ANSWER_4']; $ansCnt++; }
				if( !empty($rec['ANSWER_5']) ) { $ans["ANSWER_{$ansCnt}"] = $rec['ANSWER_5']; $ansCnt++; }
				if( !empty($rec['ANSWER_6']) ) { $ans["ANSWER_{$ansCnt}"] = $rec['ANSWER_6']; $ansCnt++; }
				if( !empty($rec['ANSWER_7']) ) { $ans["ANSWER_{$ansCnt}"] = $rec['ANSWER_7']; $ansCnt++; }
				if( !empty($rec['ANSWER_8']) ) { $ans["ANSWER_{$ansCnt}"] = $rec['ANSWER_8']; $ansCnt++; }
				if( !empty($rec['ANSWER_9']) ) { $ans["ANSWER_{$ansCnt}"] = $rec['ANSWER_9']; $ansCnt++; }
				$rec['ANSWER_0']        = $ans['ANSWER_0'];
				$rec['ANSWER_1']        = $ans['ANSWER_1'];
				$rec['ANSWER_2']        = $ans['ANSWER_2'];
				$rec['ANSWER_3']        = $ans['ANSWER_3'];
				$rec['ANSWER_4']        = $ans['ANSWER_4'];
				$rec['ANSWER_5']        = $ans['ANSWER_5'];
				$rec['ANSWER_6']        = $ans['ANSWER_6'];
				$rec['ANSWER_7']        = $ans['ANSWER_7'];
				$rec['ANSWER_8']        = $ans['ANSWER_8'];
				$rec['ANSWER_9']        = $ans['ANSWER_9'];
				$rec['ANSWER_CNT']      = $ansCnt;
				$rec['LECTURE_NAME']    = $lectureName;  // 講座名
				$rec['QUESTION_TYPE']   = $questionType; // 設問タイプ
				$rec['QUESTION_NO']     = $no++;
				if( $rec['QUESTION_ID'] == 0 ) {
					unset($rec['QUESTION_ID']);
				}
				$id = cMasterQuestion::setData($rec);
				$cnt++;
				// 紐づいている問題があったらそちらも更新する
				$rQes = cMasterQuestion::getData(array('QUESTION_RID' => $id));
				if( !empty($rQes) ) {
					$data = array();
					$rQes['QUESTION_STR']    = $rec['QUESTION_STR'];
					$rQes['QUESTION_SHORT_STR'] = $rec['QUESTION_SHORT_STR'];
					$rQes['ANSWER_CNT']      = $rec['ANSWER_CNT'];
					$rQes['ANSWER_0']        = $rec['ANSWER_0'];
					$rQes['ANSWER_1']        = $rec['ANSWER_1'];
					$rQes['ANSWER_2']        = $rec['ANSWER_2'];
					$rQes['ANSWER_3']        = $rec['ANSWER_3'];
					$rQes['ANSWER_4']        = $rec['ANSWER_4'];
					$rQes['ANSWER_5']        = $rec['ANSWER_5'];
					$rQes['ANSWER_6']        = $rec['ANSWER_6'];
					$rQes['ANSWER_7']        = $rec['ANSWER_7'];
					$rQes['ANSWER_8']        = $rec['ANSWER_8'];
					$rQes['ANSWER_9']        = $rec['ANSWER_9'];
					$rQes['ANSWER_SHORT_0']  = $rec['ANSWER_SHORT_0'];
					$rQes['ANSWER_SHORT_1']  = $rec['ANSWER_SHORT_1'];
					$rQes['ANSWER_SHORT_2']  = $rec['ANSWER_SHORT_2'];
					$rQes['ANSWER_SHORT_3']  = $rec['ANSWER_SHORT_3'];
					$rQes['ANSWER_SHORT_4']  = $rec['ANSWER_SHORT_4'];
					$rQes['ANSWER_SHORT_5']  = $rec['ANSWER_SHORT_5'];
					$rQes['ANSWER_SHORT_6']  = $rec['ANSWER_SHORT_6'];
					$rQes['ANSWER_SHORT_7']  = $rec['ANSWER_SHORT_7'];
					$rQes['ANSWER_SHORT_8']  = $rec['ANSWER_SHORT_8'];
					$rQes['ANSWER_SHORT_9']  = $rec['ANSWER_SHORT_9'];
					$id = cMasterQuestion::setData($rQes);
				}
			} else {
				// 設問が入っていなかったら削除する
				$cond = array();
				$cond['LECTURE_NAME']  = $lectureName;
				$cond['QUESTION_TYPE'] = $questionType; // 質問属性
				$cond['QUESTION_NO']   = $rec['QUESTION_NO'];
				cMasterQuestion::logDelData($cond);
			}
		}
		$atr = $form['a'];
		$atr['LECTURE_NAME']         = $lectureName;
		$atr['QUESTION_TYPE']        = $questionType; // 質問属性
		$atr['QUESTION_CNT']         = $cnt; // 質問数
		cMasterQuestionAtr::setData($atr);

		$result = new StdClass();
		$result->status = "success";
		$result->result = new StdClass();
		$result->result->lastUpdate = cDate::now();
		$result->result->form = $form;
		echo json_encode($result);
		exit;
	}
}
?>
