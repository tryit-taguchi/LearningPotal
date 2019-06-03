<?PHP
/**
 * M_SESSION情報クラス(静的クラス)
 *
 * M_SESSIONに関わる機能を提供するクラス
 *
 * @access public
 * @author Atsushi Taguchi
 * @copyright Copyright (c) 2017, Tryit
 * @version 1.00
 * @since 2017/05/02
 */
/**
 */
class cMasterSession extends cDbaDefault
{
	const TABLE_NAME  = "M_SESSION";  // 対応するテーブル名
	const PRIMARY_KEY = "SEQ_NO"; // 対応するプライマリーキー
	
	/**
	 * コンストラクタ
	 */
	function __construct() {
	}

	/**
	 * 条件作成
	 */
	static public function getWhere($search = array()) {
		list($where,$cond) = parent::getWhere($search);
		parent::setSimpleCond($where,$cond,$search,"MEMBER_ID");
		return array($where,$cond);
	}

	/**
	 * データ取得
	 * @param integer 取得するID
	 * @return array  DBから取得されたデータ
	 */
	static public function getData($search) {
		return parent::getData($search);
	}

	/**
	 * ノーマライズ（正規化）
	 * @param array 正規化するデータ
	 * @return void 無し
	 */
	static public function normalize(&$data) {
		parent::normalize($data);
		return $data;
	}

	/**
	 * バリデート（データチェック）
	 * @param array バリデートするデータ
	 * @return array エラー文言配列
	 */
	static public function validate($data) {
		$errMsg = parent::validate($data);
		
		return $errMsg;
	}

	/**
	 * データセット
	 * @param array セットするデータ
	 * @return bool TRUE=成功 FALSE=失敗
	 */
	static public function setData($data) {
		if( empty($data['MEMBER_ID']) ) return;
		$cond = array();
		$cond['MEMBER_ID']  = $data['MEMBER_ID'];
		$chkData = self::getData($cond);
		if( !empty($chkData[self::PRIMARY_KEY]) ) {
			$data[self::PRIMARY_KEY] = $chkData[self::PRIMARY_KEY];
		}
		return parent::setData($data);
	}

	/**
	 * データ論理削除
	 * @param array セットするデータ
	 * @return bool TRUE=成功 FALSE=失敗
	 */
	static public function logDelData($search) {
		if( !empty($search[self::PRIMARY_KEY]) ) {
			$id = $search[self::PRIMARY_KEY];
		} else {
			if( empty($search['MEMBER_ID']) ) return;
			$cond = array();
			$cond['MEMBER_ID']  = $search['MEMBER_ID'];
			$chkData = self::getData($cond);
			if( !empty($chkData[self::PRIMARY_KEY]) ) {
				$id = $chkData[self::PRIMARY_KEY];
			} else {
				return;
			}
		}
		return parent::logDelData($id);
	}
}
?>
