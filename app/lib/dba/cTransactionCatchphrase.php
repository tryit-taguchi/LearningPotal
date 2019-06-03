<?PHP
/**
 * T_CATCHPHRASE情報クラス(静的クラス)
 *
 * T_CATCHPHRASEに関わる機能を提供するクラス
 *
 * @access public
 * @author Atsushi Taguchi
 * @copyright Copyright (c) 2017, Tryit
 * @version 1.00
 * @since 2017/05/02
 */
/**
 */
class cTransactionCatchphrase extends cDbaDefault
{
	const TABLE_NAME  = "T_CATCHPHRASE";     // 対応するテーブル名
	const PRIMARY_KEY = "CATCHPHRASE_ID";    // 対応するプライマリーキー
	
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
		parent::setSimpleCond($where,$cond,$search,"LECTURE_NAME");
		parent::setSimpleCond($where,$cond,$search,"CATCHPHRASE_DT");
		parent::setSimpleCond($where,$cond,$search,"MEMBER_ID");
		parent::setSimpleCond($where,$cond,$search,"CATCHPHRASE_STR");
		parent::setSimpleCond($where,$cond,$search,"INS_DT");
		return array($where,$cond);
	}

	/**
	 * リスト取得
	 * @param integer 開発者ID(未指定時は全て)
	 * @return array $list=レコード
	 */
	static public function getList($search = array(),$order = "",$limit = "",$column = '*') {
		return parent::getList($search,$order,$limit,$column);
	}

	/**
	 * ページネーションリスト取得
	 * @param integer ページ番号
	 * @param array   検索条件
	 * @param strings ソート順
	 * @return array ($list=レコード $page=ページネーション)
	 */
	static public function getPageList($pageNum=null,$search = array(),$order = "",$listCount=DEFAULT_LIST_COUNT,$listMax=10) {
		return parent::getPageList($pageNum,$search,$order,$listCount,$listMax);
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
		return parent::setData($data);
	}

	/**
	 * データ論理削除
	 * @param array セットするデータ
	 * @return bool TRUE=成功 FALSE=失敗
	 */
	static public function logDelData($search) {
		$id = $search[self::PRIMARY_KEY];
		return parent::logDelData($id);
	}
}
?>
