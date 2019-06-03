<?PHP
/**
 * 文字列の圧縮クラス
 *
 * 文字列を圧縮します
 *
 * <pre>
 * </pre>
 * 
 * @access public
 * @author Atsushi Taguchi
 * @copyright Copyright (c) 2013, Tryit
 * @version 1.00
 * @since 2017/05/15
 */
/**
 */
class cCompless
{
	/**
	 * 変数
	 */

	/**
	 * コンストラクタ
	 */
	function __construct() {
	}

	/**
	 * 圧縮化
	 * @param string 暗号化したい文字列
	 * @param string パスワード
	 * @return string 暗号化後文字列
	 */
	function encode($val) {
		return base64_encode(gzdeflate(serialize($val)));
	}

	/**
	 * 復号化
	 * @param string 復号化したい文字列
	 * @param string パスワード
	 * @return string 復号化後データ
	 */
	function decode($val) {
		return unserialize(gzinflate(base64_decode($val)));
	}
}
?>