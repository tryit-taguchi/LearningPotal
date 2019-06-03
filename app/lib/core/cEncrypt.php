<?PHP
/**
 * 文字列の暗号化クラス
 *
 * 文字列を暗号化します
 *
 * <pre>
 * *********************************************************<br>
 * 　使い方<br>
 * *********************************************************<br>
 * ■暗号化する文字列
 * $str = "Hello, World!";
 * ■復号用キー（パスワード）
 * $pass = "password";
 * $enc = new cEncrypt();
 * ■暗号化
 * $encrypted = $enc->encrypt($str, $pass);
 * ■復号化
 * $decrypted = $enc->decrypt($encrypted, $pass);
 * ■閉じる
 * $enc->close();
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
class cEncrypt
{
	/**
	 * 変数
	 */
	private $td;
	private $iv;
	private $key;

	/**
	 * コンストラクタ
	 */
	function __construct() {
	}

	/**
	 * 暗号化
	 * @param string 暗号化したい文字列
	 * @param string パスワード
	 * @return string 暗号化後文字列
	 */
	function encrypt($str, $pass="C.P.A.") {
		$encrypted = openssl_encrypt($str,'aes-256-ecb',$pass);
fLog("encrypt ",$str." / " . $encrypted . " / " . urlencode(base64_encode($encrypted)));
		return urlencode(base64_encode($encrypted));
	}

	/**
	 * 復号化
	 * @param string 復号化したい文字列
	 * @param string パスワード
	 * @return string 復号化後データ
	 */
	function decrypt($str, $pass="C.P.A.") {
		$decrypted = openssl_decrypt(base64_decode(urldecode($str)),'aes-256-ecb',$pass);
fLog("decrypt ",$str." / " . base64_decode(urldecode($str)) . " / " . $decrypted);
		return $decrypted;
	}
   
	/**
	 * 処理のクローズ
	 * @param void 無し
	 * @return void 無し
	 */
	function close() {
	}
}
?>