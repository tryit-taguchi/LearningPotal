<?PHP
/**
 * CONFIG用クラス(静的クラス)
 *
 * CONFIG用クラス
 *
 * @access public
 * @author Atsushi Taguchi
 * @copyright Copyright (c) 2018, Tryit
 * @version 1.00
 * @since 2018/09/05
 */
/**
 */
class cConfig
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
	 * データ取得
	 * @param String キー
	 * @return void 無し
	 */
	static public function getData($key) {
		$data = cMasterConfig::getData(array('CKEY'=>$key));
		return $data;
	}

	/**
	 * データ取得
	 * @param String キー
	 * @return void 無し
	 */
	static public function getValue($key) {
		$data = cMasterConfig::getData(array('CKEY'=>$key));
		return $data['CVAL'];
	}

	/**
	 * データ保存
	 * @param String キー
	 * @param String 値
	 * @return void 無し
	 */
	static public function setData($key,$val) {
		if( $val == "" ) return false;
		$data = self::getData($key);
		$data['CKEY']          = $key;
		$data['CVAL']          = $val;
		$id = cMasterConfig::setData($data);
		return $id;
	}
}
?>
