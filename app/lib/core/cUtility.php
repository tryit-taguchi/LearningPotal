<?php
/**
 * ユーティリティ（静的クラス）
 *
 * 汎用ユーティリティ
 *
 * @access public
 * @author Atsushi Taguchi
 * @copyright Copyright (c) 2013, Tryit
 * @version 1.00
 * @since 2013/07/01
 */
/**
 */
class cUtility
{
	/**
	 * 文字列から数字のみを取り出す
	 * @param string 誕生日 YYYY-MM-DD
	 * @return string 干支
	 */
	static public function getNumberStr($str){
		return preg_replace('/[^0-9]/', '', $str); // 数字のみを取り出す
	}

	/**
	 * 誕生日から星座を調べる
	 * @param string 誕生日 YYYY-MM-DD
	 * @return string 星座
	 */
	static public function getSeizaFromDate($date){
		if( empty($date) ) return "";
		$tstamp = strtotime($date);
		$m = (int)date("n",$tstamp);
		$d = (int)date("j",$tstamp);
		  
		$zodiacs = array(
		// '名前', 月, 日　～　月, 日
			array('牡羊',  3, 21,  4, 19),
			array('牡牛',  4, 20,  5, 20),
			array('双子',  5, 21,  6, 21),
			array('かに',  6, 22,  7, 22),
			array('獅子',  7, 23,  8, 22),
			array('乙女',  8, 23,  9, 22),
			array('天秤',  9, 23, 10, 23),
			array('蠍',   10, 24, 11, 22),
			array('射手', 11, 23, 12, 21),
			array('山羊', 12, 22,  1, 19),
			array('水瓶',  1, 20,  2, 18),
			array('魚',    2, 19,  3, 20)
		);
		  
		foreach($zodiacs as $zodiac){
			list($name, $start_m, $start_d, $end_m, $end_d) = $zodiac;
			if(
				($m === $start_m && $d >= $start_d) ||
				($m === $end_m && $d <= $end_d)
			){
				return $name;
			}
		}
		return false;
	}

	/**
	 * 誕生日から干支を調べる
	 * @param string 誕生日 YYYY-MM-DD
	 * @return string 干支
	 */
	static public function getEtoFromDate($date){
		if( empty($date) ) return "";
		$tstamp = strtotime($date);
		$eto = array("子", "丑", "寅", "卯", "辰", "巳", "午", "未", "申", "酉", "戌", "亥");
		$thisYear = date("Y",$tstamp);
		$Y = ($thisYear + 8) % 12;
		return $eto[$Y];
	}

	/**
	 * 郵便番号から住所に変換する
	 * @param zip1のみの場合7桁 zip1とzip2の場合は3桁・4桁
	 * @return object 住所データ
	 */
	static public function getZip2Address($inzip) {
		$zip = cUtility::getNumberStr($inzip); // 数字のみを取り出す

		$db = new cDbExt();
		$cond = array();
		$cond['ZIP'] = $zip;
		$sql = "select * from M_ADDRESS where ZIP=:ZIP";
		$dat = $db->getData($sql,$cond);
		$obj = new stdClass;
	//	if( !empty($dat) ) {
			$obj->zipcode = $dat['ZIP'];
			$obj->address->pref_id = $dat['PREF_ID'];
			$obj->address->prefecture = $dat['PREF'];
			$obj->address->city    = $dat['ADDRESS_2'];
			$obj->address->town    = $dat['ADDRESS_3'];
			$obj->yomi->prefecture = $dat['ADDRESS_KANA_1'];
			$obj->yomi->city       = $dat['ADDRESS_KANA_2'];
			$obj->yomi->town       = $dat['ADDRESS_KANA_3'];
	//	}
		return $obj;
	}

	/**
	 * 消費税の計算
	 * @param Integer 税抜き金額
	 * @return Integer 税込み金額
	 */
	static public function onTax($kingaku) {
		if( $kingaku == 0 ) return 0;
		$ontax  = $kingaku*(1+commonProperty('基本設定','消費税'));
		if( ($ontax%10) == 9 ) $ontax += 1;
		return floor($ontax);
		//return round($kingaku*TAX);
	}

	/**
	 * 消費税抜きの計算
	 * @param Integer 税抜き金額
	 * @return Integer 税込み金額
	 */
	static public function offTax($kingaku) {
		if( $kingaku == 0 ) return 0;
		$offtax = $kingaku/(1+commonProperty('基本設定','消費税'));
		if( (self::onTax(ceil($offtax))%10) == 1 ) $offtax -= 1;
		return ceil($offtax);
		//return round($kingaku*TAX);
	}

	/**
	 * 指定ファイルをダウンロードさせる
	 * @param strings ファイルパス
	 * @param strings ダウンロードファイル名
	 * @return 無し
	 */
	static public function download($filepath,$filename) {
		header('Content-Type: application/force-download'); // ファイルタイプを指定
		header('Content-Length: '.filesize($filepath)); // ファイルサイズを取得し、ダウンロードの進捗を表示
		header('Content-Disposition: attachment; filename="'.$filename.'"'); // ファイルのダウンロード、リネームを指示
		readfile($filepath); // ファイルを読み込みダウンロードを実行
		exit;
	}
}
?>