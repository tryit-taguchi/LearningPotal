<?php
/**
 * エクセル（静的クラス）
 *
 * エクセル
 *
 * @access public
 * @author Atsushi Taguchi
 * @copyright Copyright (c) 2018, Tryit
 * @version 1.00
 * @since 2018/06/01
 */
/**
 参考：
  https://www.ka-net.org/blog/?p=9210
 */
require_once("lib/vendor/autoload.php");
use PhpOffice\PhpSpreadsheet\Cell;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;

class cSpreadsheet
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
		self::$book = new Spreadsheet();
		self::$book->getProperties()
				   ->setCreator("SSK")
				   ->setLastModifiedBy("TRYIT")
				   ->setCompany('SSK')
				   ->setCreated(cDate::now())
				   ->setModified(cDate::now())
				   ->setManager('A.Taguchi')
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
		$reader = new XlsxReader();
		self::$book = $reader->load($filename);
		self::$sheet = self::$book->getActiveSheet();
	}

	/**
	 * セルに書き込む (1 x 1 で始まる)
	 */
	static public function cell($col,$row,$value) {
		self::$sheet->setCellValueByColumnAndRow($col,$row,$value);
	}

	/**
	 * 配列をセルに書き込む
	 */
	static public function fromArray($pos,$arr) {
		self::$sheet->fromArray($arr, null, $pos);
	}

	/**
	 * EXCELをファイル保存する
	 */
	static public function save($filename) {
		self::$writer = new XlsxWriter(self::$book);
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

	            $dstCell = Self::stringFromColumnIndex($col) . (string)($dstRow + $row);
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
    public static function stringFromColumnIndex($columnIndex)
    {
        static $indexCache = [];

        if (!isset($indexCache[$columnIndex])) {
            $indexValue = $columnIndex;
            $base26 = null;
            do {
                $characterValue = ($indexValue % 26) ?: 26;
                $indexValue = ($indexValue - $characterValue) / 26;
                $base26 = chr($characterValue + 64) . ($base26 ?: '');
            } while ($indexValue > 0);
            $indexCache[$columnIndex] = $base26;
        }

        return $indexCache[$columnIndex];
    }

}
?>
