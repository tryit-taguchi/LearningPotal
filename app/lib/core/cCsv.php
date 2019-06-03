<?PHP
/**
 * CSVファイルの入出力クラス（EXCEL出力対応）
 * 
 * CSVの取り込み、および出力に関するクラス
 * <pre>
 * *********************************************************
 * 　使い方
 * *********************************************************
 * ■CSVファイルを読み込んでCSVダウンロードさせる
 * $csv = new cCsv();
 * $arr = $csv->readFile("test.csv");
 * $csv->setDataArray($arr);
 * $csv->setHeader(array("ヘッダ1","ヘッダ2","ヘッダ3"));
 * $csv->outputBrowser("test.csv");
 * 
 * ■CSVファイルを読み込んでEXCELダウンロードさせる
 * $csv = new cCsv();
 * $arr = $csv->readFile("test.csv");
 * $csv->setDataArray($arr);
 * $csv->setHeader(array("ヘッダ1","ヘッダ2","ヘッダ3"));
 * $csv->outputExcel('reviser.xls','empty.xls');
 * ※テンプレートのEXCELとして、上記の例だと空のエクセル(empty.xls)を用意しておくこと
 * 
 * デフォルトは
 * 文字コード:SJIS-win
 * 区切り文字:カンマ
 * 囲み文字:ダブルクォーテーション
 * </pre>
 *
 * @access public
 * @author Atsushi Taguchi
 * @copyright Copyright (c) 2013, Tryit
 * @version 1.00
 * @since 2016/06/29
 */
/**
 */
//include("cExcelReviser64.php"); // EXCEL出力用(使う場合はインクルード)
class cCsv
{
	var $data;         //CSVデータ
	var $line;         //CSVデータの一時保存データ（行単位）
	var $delimiter;    //CSVデータの区切り記号
	var $enclosure;    //CSVデータの囲み文字
	var $cfile;        //CSVファイルポインタ
	var $dataArray;    //CSVデータ用配列
	var $headerArray;  //CSVデータ用ヘッダー配列
	var $encodeType;	       //出力の文字コード
	/**
	 * コンストラクタ
	 */
	function __construct() {
		$this->data  = "";
		$this->line  = "";
		$this->delimiter = ',';
		$this->enclosure = '"';
		$this->dataArray = array();
		$this->headerArray = array();
		$this->encodeType  = "SJIS-win";
	}
	//===============================================================
	// 初期設定系
	//===============================================================
	/**
	 * CSVの区切り文字（デリミタ）の設定
	 * デフォルトはカンマ
	 * @param string デリミタ
	 * @return void 無し
	 */
	public function setDelimiter($delimiter) {
		if($delimiter == "") return;
		$this->delimiter = $delimiter;
	}
	/**
	 * データの囲み記号を設定
	 * デフォルトはデフォルトはダブルクォーテーション
	 * @param string 囲み文字
	 * @return void 無し
	 */
	public function setEnclosure($enclosure) {
		if($enclosure == "") return;
		$this->enclosure = $enclosure;
	}
	//===============================================================
	// データ入力系
	//===============================================================
	/**
	 * ファイルを読み込んで配列に返す
	 * デフォルトはデフォルトはダブルクォーテーション<br>
	 * 頭一行目にヘッダがある場合 ->readFile("text.txt",1,0);<br>
	 * @param string ファイルパス
	 * @param integer スキップする行数
	 * @param integer ヘッダがある行数 1起算 スキップする行数内に含まれる事 配列のキーになります
	 * @return array 取得したデータ配列
	 */
	public function readFile($filePath,$skipLineCnt = 0,$headLineCnt = 0) {
		$row = 1;
		$handle = fopen($filePath, "r");
		$out = array();
		$header = array();
		// 行スキップとヘッダの読み込み
		for($i=0;$i<$skipLineCnt;$i++) {
			$data = $this->fgetcsv_reg($handle);
			if( $headLineCnt == $i+1 ) {
				$num = count($data);
				for ($c=0; $c < $num; $c++) {
					$header[$c] = $data[$c];
				}
        	}
		}
		// データ本体の読み込み
		while (($data = $this->fgetcsv_reg($handle,null,$this->delimiter)) !== false) {
			$_enc_to=mb_internal_encoding();
			$_enc_from=mb_detect_order();
			mb_convert_variables($_enc_to,$_enc_from,$data);
			if( !empty($header) ) {
				// 配列をヘッダの文字列にする
				$num = count($data);
				for ($c=0; $c < $num; $c++) {
					$data2[$header[$c]] = $data[$c];
				}
				$out[] = $data2;
			} else {
				$out[] = $data;
			}
			$row++;
		}
		fclose($handle);
		return $out;
	}
	/**
	 * ファイルポインタから行を取得し、CSVフィールドを処理する
	 * デフォルトはデフォルトはダブルクォーテーション<br>
	 * 頭一行目にヘッダがある場合 ->readFile("text.txt",1,0);<br>
	 * @param resource ファイルハンドル
	 * @param integer 最大文字長
	 * @param string デリミタ
	 * @param string 囲み文字
	 * @return array １行分の配列 ファイルの終端に達した場合を含み、エラー時にFALSEを返します。
	 */
    private function fgetcsv_reg (&$handle, $length = null, $d = ',', $e = '"') {
		$d = preg_quote($d);
		$e = preg_quote($e);
		$_line = "";
		while (($eof != true)and(!feof($handle))) {
			$tmp = $this->delete_bom(empty($length) ? fgets($handle) : fgets($handle, $length));
			$tmp = mb_convert_encoding($tmp, mb_internal_encoding() , "auto");
			$_line .= $tmp;
			
			$itemcnt = preg_match_all('/'.$e.'/', $_line, $dummy);
			if ($itemcnt % 2 == 0) $eof = true;
		}
		$_csv_line = preg_replace('/(?:\\r\\n|[\\r\\n])?$/', $d, trim($_line));
		$_csv_pattern = '/('.$e.'[^'.$e.']*(?:'.$e.$e.'[^'.$e.']*)*'.$e.'|[^'.$d.']*)'.$d.'/';
		preg_match_all($_csv_pattern, $_csv_line, $_csv_matches);
		$_csv_data = $_csv_matches[1];
		for($_csv_i=0;$_csv_i<count($_csv_data);$_csv_i++){
			$_csv_data[$_csv_i]=preg_replace('/^'.$e.'(.*)'.$e.'$/s','$1',$_csv_data[$_csv_i]);
			$_csv_data[$_csv_i]=str_replace($e.$e, $e, $_csv_data[$_csv_i]);
		}
		return empty($_line) ? false : $_csv_data;
    }

	/**
	 * 文字列にBOMがあった場合は外す
	 * @param string 文字列
	 * @return array 
	 */
	private function delete_bom($str)
	{
		if (ord($str{0}) == 0xef && ord($str{1}) == 0xbb && ord($str{2}) == 0xbf) {
			$str = substr($str, 3);
		}
		return $str;
	}

	//===============================================================
	// データ出力系
	//===============================================================
	/**
	 * データ出力用の配列をセットする
	 * デフォルトはデフォルトはダブルクォーテーション<br>
	 * 頭一行目にヘッダがある場合 ->readFile("text.txt",1,0);<br>
	 * @param array データ配列（二次元）
	 * @return void 無し
	 */
	public function setDataArray(&$dataArray) {
		$this->dataArray = $dataArray;
	}
	/**
	 * ヘッダー用配列をセットする
	 * @param array ヘッダ名が書かれたデータ配列
	 * @return void 無し
	 */
	public function setHeader($headerArray) {
		$this->headerArray = $headerArray;
	}
	/**
	 * 出力用文字コードをセットする
	 * @param string 文字コード（デフォルトはSJIS-win）
	 * @return void 無し
	 */
	public function setOutputEncode($encodeType="SJIS-win") {
		$this->encodeType = $encodeType;
	}
	/**
	 * ファイルへのCSV出力(ローカルファイル)
	 * @param string ファイル名（パスを含む）
	 * @return void 無し
	 */
	public function outputFile($filename,$fouse_enclosure = "") {
		$_enc_to   = $this->encodeType;
		//$_enc_from = mb_detect_order();
		$_enc_from = 'UTF-8';
		$stream = fopen($filename, 'w');
		mb_convert_variables($_enc_to,$_enc_from,$this->headerArray);
		mb_convert_variables($_enc_to,$_enc_from,$this->dataArray);
		$cnt = count($this->dataArray);
		// ヘッダが設置されている場合は書き出す
		if( !empty($this->headerArray) ) {
			if( $cnt > 0 ) {
//fLog("ヘッダ改行あり");
				$line = $fouse_enclosure . join($fouse_enclosure.$this->delimiter.$fouse_enclosure,$this->headerArray) . $fouse_enclosure . "\r\n";
			} else {
//fLog("ヘッダ改行なし");
				$line = $fouse_enclosure . join($fouse_enclosure.$this->delimiter.$fouse_enclosure,$this->headerArray) . $fouse_enclosure;
			}
			fputs($stream, $line);
			//fputcsv($stream, $this->headerArray, $this->delimiter,$this->enclosure);
		}
//fLog("行数 : " . $cnt);
		// データ本体を書き出す
		$no = 1;
		foreach($this->dataArray as $row){
			if( !empty($fouse_enclosure) ) {
				if( $cnt > $no ) {
//fLog("ボディ改行あり");
					$line = $fouse_enclosure . join($fouse_enclosure.$this->delimiter.$fouse_enclosure,$row) . $fouse_enclosure . "\r\n";
				} else {
//fLog("ボディ改行なし");
					$line = $fouse_enclosure . join($fouse_enclosure.$this->delimiter.$fouse_enclosure,$row) . $fouse_enclosure;
				}
				$no++;
				fputs($stream, $line);
				/*
				$line = $fouse_enclosure . join($fouse_enclosure.$this->delimiter.$fouse_enclosure,$row) . $fouse_enclosure . "\n";
				fputs($stream, $line);
				*/
			} else {
			
				if( $cnt > $no ) {
					$line = $fouse_enclosure . join($fouse_enclosure.$this->delimiter.$fouse_enclosure,$row) . $fouse_enclosure . "\r\n";
				} else {
					$line = $fouse_enclosure . join($fouse_enclosure.$this->delimiter.$fouse_enclosure,$row) . $fouse_enclosure;
				}
				$no++;
//fLog($line);
				fputs($stream, $line);
				//fputcsv($stream, $row, $this->delimiter,$this->enclosure);
			}
		}
		fputs($stream, "\n");
		fclose($stream);
	}
	/**
	 * ブラウザへのCSV出力（ダウンロードファイル）
	 * @param string ファイル名
	 * @return void 無し
	 */
	public function outputBrowser($filename,$fouse_enclosure = "") {
		header("Content-Type: application/octet-stream");
		header("Content-Disposition: attachment; filename={$filename}");
		$this->outputFile('php://output',$fouse_enclosure);
		exit;
	}
	/**
	 * ブラウザへのEXCEL出力（ダウンロードファイル）
	 * @param string 出力ファイル名
	 * @param string テンプレートファイル名
	 * @param string シート名
	 * @return void 無し
	 */
	//------------------------------------------------------
	// EXCEL(xls)への出力
	//------------------------------------------------------
	public function outputExcel($filename,$readfile,$sheetName = null) {
		ini_set("memory_limit" , -1);
		set_time_limit(0);
		$basename = basename($filename);
		$basename = str_replace(".xls","",$basename);
		$xls = new Excel_Reviser;
		$xls->setInternalCharset("UTF-8");
		if( empty($sheetName) ) {
			$xls->setSheetname(0,$basename);
		}
		$rowCnt = 0;
		$colCnt = 0;
		// ヘッダが設置されている場合は書き出す
		if( !empty($this->headerArray) ) {
			foreach($this->headerArray as $col){
				$xls->addString(0,$rowCnt,$colCnt,$col);
				$colCnt++;
			}
			$colCnt = 0;
			$rowCnt++;
		}
		// データ本体を書き出す
		foreach($this->dataArray as $row){
			foreach($row as $col){
				$xls->addString(0,$rowCnt,$colCnt,$col);
				$colCnt++;
			}
			$colCnt = 0;
			$rowCnt++;
		}
		$readfile = $readfile;
		$outFile  = $filename;
		$xls->reviseFile($readfile, $outFile);
		exit;
	}
}
?>
