<?PHP
/**
 * ビット演算クラス（64bit版）
 *
 * データベース用等のビット演算のための支援クラス
 * 配列のフラグなどから整数bitを生成したり、整数から配列のフラグ化をしたりする
 * <pre>
 * *********************************************************
 * 　使い方
 * *********************************************************
 * DBのレコードでフラグが大量にある場合は、BITによる収納で高速な検索が可能になる
 * 
 * ■データベースの整数(BIGINT)をフラグ(1)が立った配列に変換する場合
 * $bit = new cBit();
 * $array = $bit->array2db(DBから引き出した値);
 * ※数値が256だったら、 arrayは {0,0,0,0,0,0,0,0,1} になる
 * ■フラグ(1)が立った配列を整数(BIGINT)に変換する場合
 * $bit = new cBit()
 * $integer = $bit->db2array(フラグ配列);
 * ※arrayが {0,0,0,0,0,0,0,0,1} だったら、数値が256になる
 * </pre>
 * 
 * @access public
 * @author Atsushi Taguchi
 * @copyright Copyright (c) 2013, Tryit
 * @version 1.00
 * @since 2013/07/01
 */
/**
 */
class cBit
{
	var $bins;		// ビット配列
	/**
	 * コンストラクタ
	 */
	function __construct() {
		$this->bins = array();
	}

	/**
	 * ＤＢビットを配列化
	 * @param integer ビット化された整数
	 * @return array 1でフラグ立てされた整数
	 */
	static public function db2array($num) {
		$ret = array();
		for($i=1;$i<=64;$i++) {
			$ret[$i] = abs($num % 2);
			$num = $num / 2;
		}
		return $ret;
	}
	/**
	 * 配列をビット化
	 * @param array 1でフラグ立てされた整数
	 * @return integer ビット化された整数
	 */
	static public function array2db($ary) {
		$ret = "";
		$ret = 0;
		$qer = array();
		for($i=1;$i<64;$i++) {
			if( $ary[$i] == 1 ) {
				$qer[] = '(1<<' . ($i-1) . ")";
				$ret += 1 << ($i-1);
			}
		}
		if( count($qer) != 0 ) {
			//$ret = "(" . join("+",$qer) . ")";
		} else {
			$ret = 0;
		}
		return $ret;
	}
	/**
	 * 数値リストをビット化
	 * @param array 数値の配列
	 * @return integer ビット化された整数
	 */
	static public function values2db($ary) {
		$bitarray = array();
		for($i=1;$i<64;$i++) {
			$bitarray[$i] = 0;
		}
		foreach($ary as $val) {
			$bitarray[$val] = 1;
		}
		return self::array2db($bitarray);
	}
	/**
	 * ビットを数値リスト化
	 * @param integer ビット化された整数
	 * @return array 数値リスト
	 */
	static public function db2values($num) {
		$bitarray = self::db2array($num);
		$retarray = array();
		for($i=1;$i<64;$i++) {
			if( $bitarray[$i] == 1 ) {
				$retarray[] = $i;
			}
		}
		return $retarray;
	}
}
?>
