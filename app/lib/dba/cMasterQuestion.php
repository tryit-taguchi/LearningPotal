<?PHP
/**
 * M_QUESTION情報クラス(静的クラス)
 *
 * M_QUESTIONに関わる機能を提供するクラス
 *
 * @access public
 * @author Atsushi Taguchi
 * @copyright Copyright (c) 2017, Tryit
 * @version 1.00
 * @since 2017/05/02
 */
/**
 */
class cMasterQuestion extends cDbaDefault
{
	const TABLE_NAME  = "M_QUESTION";     // 対応するテーブル名
	const PRIMARY_KEY = "QUESTION_ID";    // 対応するプライマリーキー
	
	/**
	 * コンストラクタ
	 */
	function __construct() {
	}

	/**
	 * 条件作成
	 */
	static public function getWhere($search = array(),$delFlg = true) {
		list($where,$cond) = parent::getWhere($search,$delFlg);
		parent::setSimpleCond($where,$cond,$search,"LECTURE_NAME");
		parent::setSimpleCond($where,$cond,$search,"QUESTION_TYPE");
		parent::setSimpleCond($where,$cond,$search,"QUESTION_NO");
		parent::setSimpleCond($where,$cond,$search,"QUESTION_RID");
		parent::setMultiCond($where,$cond,$search,"QUESTION_NO_LIST","QUESTION_NO",'in');
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
	static public function getData($search,$delFlg = true) {
		return parent::getData($search,"",$delFlg);
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
		if( empty($data['QUESTION_TYPE']) ) return;
		if( empty($data['QUESTION_NO']) ) return;
		$cond = array();
		$cond['LECTURE_NAME']  = $data['LECTURE_NAME'];
		$cond['QUESTION_TYPE'] = $data['QUESTION_TYPE']; // 質問属性
		$cond['QUESTION_NO']   = $data['QUESTION_NO'];
		$chkData = self::getData($cond,false);
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
			if( empty($search['LECTURE_NAME']) ) return;
			if( empty($search['QUESTION_TYPE']) ) return;
			if( empty($search['QUESTION_NO']) ) return;
			$cond = array();
			$cond['LECTURE_NAME']  = $search['LECTURE_NAME'];
			$cond['QUESTION_TYPE'] = $search['QUESTION_TYPE']; // 質問属性
			$cond['QUESTION_NO']   = $search['QUESTION_NO'];
			$chkData = self::getData($cond);
			if( !empty($chkData[self::PRIMARY_KEY]) ) {
				$id = $chkData[self::PRIMARY_KEY];
			} else {
				return;
			}
		}
		return parent::logDelData($id);
	}

	/**
	 * データ論理削除（QuestionType一括）
	 * @param array セットするデータ
	 * @return bool TRUE=成功 FALSE=失敗
	 */
	static public function logDelQuestionType($search) {
		if( empty($search['LECTURE_NAME']) ) return;
		if( empty($search['QUESTION_TYPE']) ) return;
		$db = new cDbExt();
		$cond = array();
		$cond['LECTURE_NAME']  = $search['LECTURE_NAME'];
		$cond['QUESTION_TYPE'] = $search['QUESTION_TYPE'];
		$data = array();
		$data['DEL_FLG'] = 1;
		$db->setUpdateTable(self::TABLE_NAME);
		$db->setUpdateList($data);
		$db->setUpdateWhere($cond);
		$db->updateExec();
		$ret = $data[self::PRIMARY_KEY];
		return $ret;
	}
}
?>
