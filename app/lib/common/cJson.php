<?PHP
/**
 * Json用クラス(静的クラス)
 *
 * Json用クラス
 *
 * @access public
 * @author Atsushi Taguchi
 * @copyright Copyright (c) 2018, Tryit
 * @version 1.00
 * @since 2018/09/05
 */
/**
 */
class cJson
{
	/**
	 * コンストラクタ
	 */
	function __construct() {
	}

	/**
	 * Jsonでエラー出力
	 * @param String キー
	 * @return boolian
	 */
	static public function error($str) {
		$result = new StdClass();
		$result->status = "error";
		$result->result = new StdClass();
		$result->result->errorMessage = $str;
		echo json_encode($result);
		exit;
	}

	/**
	 * Jsonでエラー出力
	 * @param String キー
	 * @return boolian
	 */
	static public function success($str) {
		$result = new StdClass();
		$result->status = "success";
		$result->result = new StdClass();
		$result->result->errorMessage = $str;
		echo json_encode($result);
		exit;
	}
}
?>
