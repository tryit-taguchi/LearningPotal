<?PHP
/**
 * M_MEMBER情報クラス(静的クラス)
 *
 * M_MEMBERに関わる機能を提供するクラス
 *
 * @access public
 * @author Atsushi Taguchi
 * @copyright Copyright (c) 2017, Tryit
 * @version 1.00
 * @since 2017/05/02
 */
/**
 */
class cMasterMember extends cDbaDefault
{
	const TABLE_NAME  = "M_MEMBER";     // 対応するテーブル名
	const PRIMARY_KEY = "MEMBER_ID";    // 対応するプライマリーキー
	
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
		parent::setSimpleCond($where,$cond,$search,"SEAT_CD");
		parent::setSimpleCond($where,$cond,$search,"LECTURE_DT");
		parent::setSimpleCond($where,$cond,$search,"LECTURE_TYPE");
		parent::setSimpleCond($where,$cond,$search,"SITE_NAME");
		parent::setSimpleCond($where,$cond,$search,"MEMBER_NAME");
		parent::setSimpleCond($where,$cond,$search,"COMPANY_NAME");
		parent::setSimpleCond($where,$cond,$search,"PLACE_NAME");
		parent::setSimpleCond($where,$cond,$search,"LECTURE_NAME");
		parent::setDateFromToWhere($where,$cond,$search["LECTURE_DT_S"],$search["LECTURE_DT_E"],"LECTURE_DT"); // 日付範囲検索
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
		return parent::getData($search,"MEMBER_ID desc");
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
		if( empty($data['LECTURE_NAME']) ) return;
		if( empty($data['SITE_NAME']) ) return;
		$cond = array();
		$cond['LECTURE_NAME']  = $data['LECTURE_NAME'];
		$cond['LECTURE_DT']    = $data['LECTURE_DT'];
		$cond['LECTURE_TYPE']  = $data['LECTURE_TYPE'];
		$cond['SEAT_CD']       = $data['SEAT_CD'];
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
		$id = $search[self::PRIMARY_KEY];
		return parent::logDelData($id);
	}
}
?>
