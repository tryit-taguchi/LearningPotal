<?PHP
/**
 * メンバー用クラス(静的クラス)
 *
 * メンバー用クラス
 *
 * @access public
 * @author Atsushi Taguchi
 * @copyright Copyright (c) 2018, Tryit
 * @version 1.00
 * @since 2018/09/05
 */
/**
 */
class cMember
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
	static public function setExcelData($lectureName,$line) {
		$data = array();
		$data['SEAT_CD']      = strim($line['A']);              // 席番CD
		$data['GROUP_CD']     = codeCnv($line['B']);            // グループCD
		$data['LECTURE_DT']   = dateCnv($line['C']);            // 日程
		$data['SITE_NAME']    = strim($line['D']);              // 会場
		$data['LECTURE_TYPE'] = typeCnv($line['E'],$line['F']); // 前半・後半
		$data['COMPANY_NAME'] = strim($line['G']);              // 法人名
		$data['MEMBER_NAME']  = strim($line['H']);              // 利用者名
		$data['DIV_NAME']     = strim($line['I']);              // 部署
		$data['PLACE_NAME']   = strim($line['J']);              // 店舗
		$data['POST_NAME']    = strim($line['K']);              // 役職
		// 必須項目が入っていないデータは除く（端末・日付・利用者名）
		if( $data['SEAT_CD'] == '' 
		 || $data['LECTURE_DT'] == '' 
		 || $data['COMPANY_NAME'] == '' 
		 || $data['MEMBER_NAME'] == ''
		 || $data['LECTURE_TYPE'] == 0 ) {
			return 'empty';
		}
		$cond = array();
		$cond['SEAT_CD']    = $data['SEAT_CD'];
		$cond['LECTURE_DT'] = $data['LECTURE_DT'];
		$cond['SITE_NAME']  = $data['SITE_NAME'];
		$chkData = cMasterMember::getData($cond);
		if( !empty($chkData['MEMBER_ID']) ) {
			$data['MEMBER_ID'] = $chkData['MEMBER_ID'];
		}
		$id = cMasterMember::setData($data);
		return $id;
	}

	/**
	 * インポート
	 * @param String $lectureName
	 * @param String $questionType
	 * @param Array  $files
	 * @return void 無し
	 */
	static public function importData($lectureName,$files,$memberLast) {
		if($files["file"]["tmp_name"]){
			$result = new StdClass();
			// アップロードされたファイルの取得
			list($file_name,$file_type) = explode(".",$files['file']['name']);
			if( $file_type == "xls" ) {
				$result->status = "error";
				$result->result = new StdClass();
				$result->result->errorMessage = "古いEXCEL形式のファイルは対応しておりません。xlsxにて保存してください。";
				echo json_encode($result);
				exit;
			}
			if( $file_type != "xlsx" ) {
				$result->status = "error";
				$result->result = new StdClass();
				$result->result->errorMessage = "EXCEL以外のファイルが指定されています。";
				echo json_encode($result);
				exit;
			}
			cExcel::load($files["file"]["tmp_name"]);
			$srcArr = cExcel::toArray();

			// ファイルチェック
			if( $srcArr[1]['A'] != "受講者" ) {
				$result->status = "error";
				$result->result = new StdClass();
				$result->result->errorMessage = "受講者ではないEXCELファイルです。";
				echo json_encode($result);
				exit;
			}

			// DBに収納する
			$row = 0;
			$imprtCount = 0;
			$dataRows   = count($srcArr);
			foreach($srcArr as $line) {
				$row++;
				if( $row <= 2 ) continue; // ヘッダは無視する
				// レコードに（端末・日付・会場）が既に割り当てられているかチェックし、割り当てられていたら上書きする
				$ret = self::setExcelData($lectureName,$line); // 保存する

				$imprtCount++;
			}
			cConfig::setData($memberLast,cDate::now());
			$result = new StdClass();
			$result->status = "success";
			$result->result = new StdClass();
			$result->result->lastUpdate  = cConfig::getValue($memberLast);
			$result->result->importCount = $imprtCount;
			$result->result->dataRows    = $dataRows;
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
	static public function exportData($lectureName,$lectureDt,$siteName,$memberLast) {

		$cond = array();
		if( !empty($lectureDt) ) {
			$cond['LECTURE_DT'] = $lectureDt;
			$cond['SITE_NAME']    = $siteName;
		}
		$list = cMasterMember::getList($cond,"LECTURE_DT,SITE_NAME,TERM_CD");
		cExcel::load("template/excel/member.xlsx");
		$weekList = [
		  '日', //0
		  '月', //1
		  '火', //2
		  '水', //3
		  '木', //4
		  '金', //5
		  '土', //6
		];
		$no = 3;
		foreach($list as $rec) {
			cExcel::cell( 1, $no,$rec['SEAT_CD']);                   // 座席CD
			cExcel::cell( 2, $no,$rec['GROUP_CD']);                  // グループCD
			$week = $weekList[date("w",strtotime($rec['LECTURE_DT']))];
			cExcel::cell( 3, $no,PHPExcel_Shared_Date::PHPToExcel(str_replace("-","/",$rec['LECTURE_DT'])));                // 日程
			cExcel::cellFormat( 3, $no,"yyyy/m/d (aaa)");            // 日程書式
			cExcel::cell( 4, $no,$rec['SITE_NAME']);                 // 会場
			cExcel::cell( 5, $no,$rec['LECTURE_TYPE']==1?'前半':''); // 前半
			cExcel::cell( 6, $no,$rec['LECTURE_TYPE']==2?'後半':''); // 後半
			cExcel::cell( 7, $no,$rec['COMPANY_NAME']);              // 法人名
			cExcel::cell( 8, $no,$rec['MEMBER_NAME']);               // 利用者名
			cExcel::cell( 9, $no,$rec['DIV_NAME']);                  // 部署
			cExcel::cell(10, $no,$rec['PLACE_NAME']);                // 店舗
			cExcel::cell(11, $no,$rec['POST_NAME']);                 // 役職
			$no++;
		}
		$tmpDir = sys_get_temp_dir();
		$memberCnt = count($list);
		$nowDate   = date("Y-m-d");
		if( !empty($lectureDt) ) {
			$subName = "{$siteName}_{$lectureDt}_{$memberCnt}名";
		} else {
			$subName = "すべて_{$nowDate}_{$memberCnt}名";
		}
		$exceiFileName = "受講者_{$subName}.xlsx";
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
	static public function getData($lectureName,$lectureDt,$siteName) {
		$cond = array();
		$cond['LECTURE_NAME'] = $lectureName;
		$cond['LECTURE_DT']   = $lectureDt;
		$cond['SITE_NAME']    = $siteName; // 会場名
		$tmpList = cMasterMember::getList($cond,"MEMBER_ID");
		return $tmpList;
	}

	/**
	 * 保存
	 * @param String $lectureName
	 * @param String $questionType
	 * @return void 無し
	 */
	static public function saveData($lectureName,$lectureDt,$siteName,$form,$memberLast) {
		$cnt = 0;
		foreach($form['m'] as $rec) {
			if( !empty($rec['SEAT_CD']) ) {
				//$rec['SITE_NAME']       = $siteName;     // 会場名
				//$rec['LECTURE_NAME']    = $lectureName;  // 講座名
				$id = cMasterMember::setData($rec);
				$cnt++;
			}
		}
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
