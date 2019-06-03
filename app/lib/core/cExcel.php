<?php
/**
 * エクセル（静的クラス）
 *
 * エクセル
 *
 * @access public
 * @author Atsushi Taguchi
 * @copyright Copyright (c) 2013, Tryit
 * @version 1.00
 * @since 2013/07/01
 */
/**
 参考：
 http://qiita.com/suin/items/7a8d0979b7675d6fd05b
 https://www.marineroad.com/staff-blog/14936.html
 */
require_once("lib/PHPExcel/PHPExcel.php");
class cExcel
{
	/**
	 * EXCEL用変数
	 */
	static public $book;
	static public $sheet;
	static public $writer;

	/**
	 * EXCELを生成する
	 */
	static public function create($category,$subject) {
		self::$book = new PHPExcel();
		self::$book->getProperties()
				   ->setCreator("TRYIT")
				   ->setLastModifiedBy("TRYIT")
				   ->setCompany('company')
				   ->setCreated(cDate::now())
				   ->setModified(cDate::now())
				   ->setManager('AT')
				   ->setTitle($category)
				   ->setSubject($subject)
				   ->setDescription("")
				   ->setKeywords($subject)
				   ->setCategory($category);
		self::$sheet = self::$book->getActiveSheet();
	}

	/**
	 * テンプレートを読み込む
	 */
	static public function load($filename) {
		self::$book = PHPExcel_IOFactory::load($filename);
		self::$sheet = self::$book->getActiveSheet();
	}

	/**
	 * シートを変える
	 */
	static public function setActiveSheetIndex($idx) {
		self::$book->setActiveSheetIndex($idx);
		self::$sheet = self::$book->getActiveSheet();
	}

	/**
	 * シート非表示
	 * true=非表示
	 */
	static public function hiddenActiveSheet($flg) {
		if( $flg == true ) {
			self::$sheet->setSheetState(PHPExcel_Worksheet::SHEETSTATE_HIDDEN);
		}
	}

	/**
	 * セルに書き込む (1 x 1 で始まる)
	 */
	static public function cell($col,$row,$value = null) {
		if( $value != null ) {
			self::$sheet->setCellValueByColumnAndRow($col-1,$row,$value);
			return null;
		} else {
			return self::$sheet->getCellByColumnAndRow( $col-1,$row )->getValue();
		}
	}

	/**
	 * セルに書式を書き込む (1 x 1 で始まる)
	 */
	static public function cellFormat($col,$row,$format = null) {
		$colStr = self::excelColName($col-1);
		if( $value != null ) {
			self::$sheet->getStyle($colStr.$row)->getNumberFormat()->setFormatCode($format);
			return null;
		} else {
			return self::$sheet->getStyle($colStr.$row)->getNumberFormat();
		}
	}

	/**
	 * 配列をセルに書き込む
	 */
	static public function fromArray($pos,$arr) {
		self::$sheet->fromArray($arr, null, $pos);
	}

	/**
	 * セルから配列に読み込む
	 */
	static public function toArray() {
		// 引数
		// 1.セルが存在しない場合、配列エントリに返される値
		// 2.数式として文字列的に読み込むならfalse、数式に計算された結果を読み込むならtrue
		// 3.セル値に書式を適用する
		// 4.falseの場合は0から数えてカウントされた行と列の単純な配列を返します trueの場合は実際の行IDと列IDでインデックスを付けた行と列を返します
		return self::$sheet->toArray(null,true,true,true);
	}

	/**
	 * EXCELをファイル保存する
	 */
	static public function save($filename) {
		self::$writer = PHPExcel_IOFactory::createWriter(self::$book, 'Excel2007');
		self::$writer->save($filename);
	}

	/**
	 * EXCELをクローズする
	 */
	static public function close() {
		self::$book->disconnectWorksheets();
		//unset(self::$writer);
		//unset(self::$sheet);
		//unset(self::$book);
	}
	
	/**
	 * 行を完全コピーする
	 *
	 * http://blog.kotemaru.org/old/2012/04/06.html より
	 * @param PHPExcel_Worksheet $sheet
	 * @param int $srcRow
	 * @param int $dstRow
	 * @param int $height
	 * @param int $width
	 * @throws PHPExcel_Exception
	 */
	static public function copyRows(
//	    PHPExcel_Worksheet $sheet,
	    $srcRow,
	    $dstRow,
	    $height,
	    $width
	) {
		$sheet = self::$sheet;
	    for ($row = 0; $row < $height; $row++) {
	        // セルの書式と値の複製
	        for ($col = 0; $col < $width; $col++) {
	            $cell = $sheet->getCellByColumnAndRow($col, $srcRow + $row);
	            $style = $sheet->getStyleByColumnAndRow($col, $srcRow + $row);

	            $dstCell = PHPExcel_Cell::stringFromColumnIndex($col) . (string)($dstRow + $row);
	            $sheet->setCellValue($dstCell, $cell->getValue());
	            $sheet->duplicateStyle($style, $dstCell);
	        }

	        // 行の高さ複製。
	        $h = $sheet->getRowDimension($srcRow + $row)->getRowHeight();
	        $sheet->getRowDimension($dstRow + $row)->setRowHeight($h);
	    }

	    // セル結合の複製
	    // - $mergeCell="AB12:AC15" 複製範囲の物だけ行を加算して復元。
	    // - $merge="AB16:AC19"
	    foreach ($sheet->getMergeCells() as $mergeCell) {
	        $mc = explode(":", $mergeCell);
	        $col_s = preg_replace("/[0-9]*/", "", $mc[0]);
	        $col_e = preg_replace("/[0-9]*/", "", $mc[1]);
	        $row_s = ((int)preg_replace("/[A-Z]*/", "", $mc[0])) - $srcRow;
	        $row_e = ((int)preg_replace("/[A-Z]*/", "", $mc[1])) - $srcRow;

	        // 複製先の行範囲なら。
	        if (0 <= $row_s && $row_s < $height) {
	            $merge = $col_s . (string)($dstRow + $row_s) . ":" . $col_e . (string)($dstRow + $row_e);
	            $sheet->mergeCells($merge);
	        }
	    }
	}

	/*
	 EXCELのdate m/d/Y を Y-m-d に変換する
	*/
	static public function dateCnv($str) {
		if( empty($str) ) return "";
		$tmp = explode("/",$str);
		return $tmp[2] . '-' . $tmp[0] . '-' . $tmp[1];
	}

	/**
	 * Excelの列名を取得します。
	 * @param $colIndex
	 * @return string
	 */
	public static function excelColName($colIndex)
	{
		if (!is_numeric($colIndex)) {
			return "";
		}

		$retString = "";
		$alphabetNum = 26;

		$currentColIndex = $colIndex;
		while (true) {
			$alphabetIndex = $currentColIndex % $alphabetNum;
			$alphabet = chr(ord("A") + $alphabetIndex);
			$retString = $alphabet . $retString;
			if ($currentColIndex < $alphabetNum) {
				break;
			}
			$currentColIndex = intval(floor(($currentColIndex - $alphabetNum) / $alphabetNum));
		};

		return $retString;
	}

}
?>
