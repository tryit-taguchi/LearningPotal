<?PHP
/**
 * データベースアクセスデフォルトクラス(静的クラス)
 *
 * データベースアクセスデフォルトに関わる機能を提供するクラス
 *
 * @access public
 * @author Atsushi Taguchi
 * @copyright Copyright (c) 2017, Tryit
 * @version 1.00
 * @since 2017/05/02
 */
/**
 */
class cDbaDefault
{
	/**
	 * 定数・変数
	 */
	static private $attachTable;

	/**
	 * コンストラクタ
	 */
	function __construct() {
	}

	/**
	 * テーブルのアタッチメント
	 * @param array  テーブルがキーでデータがカラムの配列
	 * @return array
	 */
	static public function attachTable($search = array()) {
		self::$attachTable[] = $search;
	}

	/**
	 * テーブルのアタッチメントデータ取得
	 * @param array  テーブルがキーでデータがカラムの配列
	 * @return array
	 */
	static private function getAttachTable() {
		if( !is_array(self::$attachTable) ) return array('','');
		$tableName = static::TABLE_NAME;
		$tArr = array();
		$wArr = array();
		foreach(self::$attachTable as $rec ) {
			foreach($rec as $aTable => $column ) {
				$tArr[] = $aTable;
				$wArr[] = $tableName . "." . $column . "=" .  $aTable . "." . $column;
			}
		}

		$attachTable = join(",",$tArr);
		$joinWhere   = join(" and ",$wArr);
		if( !empty($attachTable) ) $attachTable = ','.$attachTable;
		if( !empty($joinWhere) ) $joinWhere = $joinWhere . ' and ';

		return array($attachTable,$joinWhere);
	}

	/**
	 * リスト取得
	 * @param array  条件配列orプライマリーキー
	 * @param string ソート条件
	 * @param limit  リミット条件
	 * @return array $list=レコード
	 */
	static public function getList($search = array(),$order = "",$limit = "",$column = "*") {
		$db = new cDbExt();
		list($where,$cond) = static::getWhere($search); // 検索条件の作成
		$addWhere = "";
		if( !empty($where) ) {
			$addWhere = join(" and " , $where);
		}
		if( !empty($limit) ) {
			$limit = "limit " . $limit;
		}
		if( !empty($order) ) {
			$order = "order by " . $order;
		}
		if( is_array($column) ) {
			$column = join(',',$column);
		}
		$tableName = static::TABLE_NAME;
		list($attachTable,$joinWhere) = self::getAttachTable();
		$sql = "select {$column} from {$tableName} {$attachTable} where {$joinWhere} {$addWhere} {$order} {$limit}";
		$list = $db->getList($sql,$cond);
		self::$attachTable = null;
		return $list;
	}

	/**
	 * レコード数取得
	 * @param array  条件配列orプライマリーキー
	 * @return array $list=レコード
	 */
	static public function getCount($search = array(),$order = "",$limit = "") {
		$db = new cDbExt();
		list($where,$cond) = static::getWhere($search); // 検索条件の作成
		$addWhere = "";
		if( !empty($where) ) {
			$addWhere = join(" and " , $where);
		}
		$tableName = static::TABLE_NAME;
		list($attachTable,$joinWhere) = self::getAttachTable();
		$sql = "select count(".static::PRIMARY_KEY.") as CNT from {$tableName} {$attachTable} where {$joinWhere} {$addWhere} {$order} {$limit}";
		$rec = $db->getData($sql,$cond);
		self::$attachTable = null;
		return $rec['CNT'];
	}

	/**
	 * ページネーションリスト取得
	 * @param integer ページ番号
	 * @param array   検索条件
	 * @param strings ソート順
	 * @return array ($list=レコード $page=ページネーション)
	 */
	static public function getPageList($pageNum=null,$search = array(),$order = "",$listCount=DEFAULT_LIST_COUNT,$listMax=10) {
		$db = new cDbExtPagination();
		list($where,$cond) = static::getWhere($search); // 検索条件の作成
		$addWhere = "";
		if( !empty($where) ) {
			$addWhere = join(" and " , $where);
		}
		$tableName = static::TABLE_NAME;
		list($attachTable,$joinWhere) = self::getAttachTable();
		$sql = "select * from {$tableName} {$attachTable} where {$joinWhere} {$addWhere}";
		$list = $db->getList($sql,$cond,$order,$pageNum,$listCount,$listMax);
		// ページネーションデータの取得
		$page = $db->getPageInfo();
		self::$attachTable = array();
		return array($list,$page);
	}

	/**
	 * データ取得
	 * @param array  条件配列orプライマリーキー
	 * @return array  DBから取得されたデータ
	 */
//	static public function getData($search,$columnList=array()) {
	static public function getData($search,$order = "",$delFlg = true) {
		$db = new cDbExt();
		list($where,$cond) = static::getWhere($search,$delFlg); // 検索条件の作成
		$addWhere = "";
		if( !empty($where) ) {
			$addWhere = join(" and " , $where);
		}
		if( !empty($order) ) {
			$order = "order by " . $order;
		}
		$tableName = static::TABLE_NAME;
		list($attachTable,$joinWhere) = self::getAttachTable();
		$sql = "select * from {$tableName} {$attachTable} where {$joinWhere} {$addWhere} {$order} limit 1";
		$data = $db->getData($sql,$cond);
		self::$attachTable = null;
		return $data;
	}

	/**
	 * データセット
	 * @param array セットするデータ
	 * @return bool TRUE=成功 FALSE=失敗
	 */
	static public function setData($data) {
		$db = new cDbExt();
		$ret = TRUE;

		if( empty($data[static::PRIMARY_KEY]) ) {
			//fLog("インサート");
			$ret = self::insData($data);
		} else {
			//fLog("アップデート");
			$ret = self::updData($data);
		}
		return $ret;
	}

	/**
	 * データインサート
	 * @param array セットするデータ
	 * @return bool TRUE=成功 FALSE=失敗
	 */
	static public function insData($data) {
		$db = new cDbExt();
		$ret = TRUE;
		$data['INS_DT'] = !empty($data['INS_DT'])?$data['INS_DT']:date("Y-m-d H:i:s");
		$data['UPD_DT'] = date("Y-m-d H:i:s");
		$db->setInsertTable(static::TABLE_NAME);
		//fLog($data);
		$db->setInsertList($data);
		$db->insertExec();
		$ret = $db->getseq();
		return $ret;
	}

	/**
	 * データアップデート
	 * @param array セットするデータ
	 * @return bool TRUE=成功 FALSE=失敗
	 */
	static public function updData($data) {
		$db = new cDbExt();
		$cond = array();
		$cond[static::PRIMARY_KEY] = $data[static::PRIMARY_KEY];
		$data['UPD_DT'] = date("Y-m-d H:i:s");
		$db->setUpdateTable(static::TABLE_NAME);
		$db->setUpdateList($data);
		$db->setUpdateWhere($cond);
		$db->updateExec();
		$ret = $data[static::PRIMARY_KEY];
		return $ret;
	}

	/**
	 * データ論理削除
	 * @param array セットするデータ
	 * @return bool TRUE=成功 FALSE=失敗
	 */
	static protected function logDelData($id) {
		$data = array();
		$data[static::PRIMARY_KEY] = (int)$id;
		$data['UPD_DT'] = date("Y-m-d H:i:s");
		$data['DEL_FLG'] = 1;
		return self::updData($data);
	}

	/**
	 * 指定カラムの最大値を取り出す
	 * @param String カラム名
	 * @return Integer 最大値
	 */
	static public function getMax($column,$where=null) {
		$db = new cDbExt();
		$tableName = static::TABLE_NAME;
		$sql = "select max({$column}) as MAX_VAL from {$tableName} where DEL_FLG=0 {$where}";
		$data = $db->getData($sql);
		return $data['MAX_VAL'];
	}

	/**
	 * 月ごとの個数を取り出す
	 * @param array  条件配列orプライマリーキー
	 * @param String 日付カラム名
	 * @return 配列
	 */
	static public function getYmCountList($search,$column,$order = "") {
		$db = new cDbExt();
		if( empty($order) ) {
			$order = "COUNT_YM desc";
		}
		list($where,$cond) = static::getWhere($search); // 検索条件の作成
		$addWhere = "";
		if( !empty($where) ) {
			$addWhere = join(" and " , $where);
		}
		if( !empty($limit) ) {
			$limit = "limit " . $limit;
		}
		if( !empty($order) ) {
			$order = "order by " . $order;
		}
		$tableName = static::TABLE_NAME;
		list($attachTable,$joinWhere) = self::getAttachTable();
		$sql = "select  DATE_FORMAT({$column}, '%Y-%m') as COUNT_YM, COUNT({$column}) as COUNT from {$tableName} {$attachTable} where {$joinWhere} {$addWhere} and {$column} is not null group by COUNT_YM {$order}";
		$list = $db->getList($sql,$cond);
		self::$attachTable = null;
		return $list;
	}

	/**
	 * 年ごとの個数を取り出す
	 * @param array  条件配列orプライマリーキー
	 * @param String 日付カラム名
	 * @return 配列
	 */
	static public function getYCountList($search,$column,$order = "") {
		$db = new cDbExt();
		if( empty($order) ) {
			$order = "COUNT_Y desc";
		}
		list($where,$cond) = static::getWhere($search); // 検索条件の作成
		$addWhere = "";
		if( !empty($where) ) {
			$addWhere = join(" and " , $where);
		}
		if( !empty($limit) ) {
			$limit = "limit " . $limit;
		}
		if( !empty($order) ) {
			$order = "order by " . $order;
		}
		$tableName = static::TABLE_NAME;
		list($attachTable,$joinWhere) = self::getAttachTable();
		$sql = "select  DATE_FORMAT({$column}, '%Y') as COUNT_Y, COUNT({$column}) as COUNT from {$tableName} {$attachTable} where {$joinWhere} {$addWhere} and {$column} is not null group by COUNT_Y {$order}";
		$list = $db->getList($sql,$cond);
		self::$attachTable = null;
		return $list;
	}

	/**
	 * ノーマライズ（正規化）
	 * @param array 正規化するデータ
	 * @return void 無し
	 */
	static public function normalize(&$data) {
		$data[static::PRIMARY_KEY] = (int)$data[static::PRIMARY_KEY];
		return $data;
	}

	/**
	 * バリデート（データチェック）
	 * @param array バリデートするデータ
	 * @return array エラー文言配列
	 */
	static public function validate($data) {
		$errMsg = array();
		return $errMsg;
	}

	/**
	 * 条件作成
	 * @param array 検索条件
	 * @return array 追加 where と cond
	 */
	static public function getWhere($search = array(),$delFlg = true) {
		$cond = array();
		$where = array();
		if( !is_array($search) ) {
			if( !empty($search) ) {
				$where[]    = static::TABLE_NAME.'.'.static::PRIMARY_KEY.'=:'.static::PRIMARY_KEY;
				$cond[static::PRIMARY_KEY] = $search;
			}
		} else {
			if( !empty($search[static::PRIMARY_KEY]) ) {
				$where[]    = static::TABLE_NAME.'.'.static::PRIMARY_KEY.'=:'.static::PRIMARY_KEY;
				$cond[static::PRIMARY_KEY] = $search[static::PRIMARY_KEY];
			}
		}
		if( $delFlg ) {
			$where[]    = static::TABLE_NAME.".DEL_FLG=:DEL_FLG";
			$cond['DEL_FLG'] = 0;
		}
		return array($where,$cond);
	}

	/**
	 * 単純条件設定
	 * @param array 検索条件
	 * @return array 追加 where と cond
	 */
	static public function setSimpleCond(&$where,&$cond,$search,$column,$compar = '=') {
		$tableName = static::TABLE_NAME;
		if( empty($search[$column]) ) return;
		$where[]    = "{$tableName}.{$column} {$compar} :{$column}";
		if( mb_strtolower(trim($compar)) == 'like' ) {
			$cond[$column] = "%" . $search[$column]. "%";
		} else {
			$cond[$column] = $search[$column];
		}
	}

	/**
	 * 単純条件設定
	 * @param array 検索条件
	 * @return array 追加 where と cond
	 */
	static public function setSimpleCond2(&$where,&$cond,$search,$column,$compar = '=') {
		$tableName = static::TABLE_NAME;
		if( !isset($search[$column]) ) return;
		$where[]    = "{$tableName}.{$column} {$compar} :{$column}";
		if( mb_strtolower(trim($compar)) == 'like' ) {
			$cond[$column] = "%" . $search[$column]. "%";
		} else {
			$cond[$column] = $search[$column];
		}
	}

	/**
	 * 複数条件設定
	 * @param array 検索条件
	 * @return array 追加 where と cond
	 */
	static public function setMultiCond(&$where,&$cond,$search,$listName,$column,$compar = '=') {
		$tableName = static::TABLE_NAME;
		if( !isset($search[$listName]) ) return;
		if( empty($search[$listName]) ) return;
		$where[]    = "{$tableName}.{$column} {$compar} ('".join("' , '",$search[$listName])."')";
	}

	/**
	 * 日付範囲条件（FROM日付とTO日付からカラム）
	 * @param array  where配列（参照）
	 * @param array  cond配列（参照）
	 * @param string カラム名
	 * @param string from日付
	 * @param string to日付
	 * @return 無し
	 */
	static public function setDateFromToWhere(&$where,&$cond,$from,$to,$column) {
		$tableName = static::TABLE_NAME;
		$from    = trim($from);
		$to      = trim($to);
		if( empty($from) && empty($to) ) {
			return;
		}

		$from = str_replace('/','-',$from);
		$to   = str_replace('/','-',$to);
		if( !empty($from) && empty($to) ) {
			$where[] = "{$tableName}.{$column} >= :{$column}_FROM";
			$cond["{$column}_FROM"] = $from;
			return;
		}
		if( empty($from) && !empty($to) ) {
			$tmp = date("Y-m-d",strtotime($to . ' +1 day '));
			$where[] = "{$tableName}.{$column} < :{$column}_TO";
			$cond['{$column}_TO'] = $tmp;
			return;
		}
		if( !empty($from) && !empty($to) ) {
			$tmp = $from;
			if( $to < $from ) {
				$from = $to;
				$to = $tmp;
			}
			$tmp = date("Y-m-d",strtotime($to . ' +1 day '));
			$cond["{$column}_TO"] = $tmp;
			$where[] = "( {$tableName}.{$column} >= :{$column}_FROM and {$tableName}.{$column} < :{$column}_TO ) ";
			$cond["{$column}_FROM"] = $from;
		}
		return;
	}

	/**
	 * 日付範囲条件（日付からFROMカラムとTOカラム）
	 * @param array  where配列（参照）
	 * @param array  cond配列（参照）
	 * @param string 日付
	 * @param string fromカラム名
	 * @param string toカラム名
	 * @return 無し
	 */
	static public function setDateWhereFromTo(&$where,&$cond,$search,$column,$from,$to) {
		if( !is_array($search) ) return;
		$date = $search[$column];
		if( empty($date)) {
			return;
		}
		$tableName = static::TABLE_NAME;
		$fromDt = date('Y-m-d',strtotime($date));
		$toDt   = date('Y-m-d',strtotime($date . " -1 day"));
		if( strpos($from,'.') === FALSE ) {
			$from2  = $from;
			$where[]    = "{$tableName}.{$from}<=:{$from2}";
		} else {
			$from2 = str_replace(".","_",$from);
			$where[]    = "{$from}<=:{$from2}";
		}
		$cond[$from2] = $fromDt;
		if( strpos($to,'.') === FALSE ) {
			$to2  = $to;
			$where[]    = "{$tableName}.{$to}>:{$to2}";
		} else {
			$to2 = str_replace(".","_",$to);
			$where[]    = "{$to}>:{$to2}";
		}
		$cond[$to2]   = $toDt;
		return;
	}

	/**
	 * 月指定条件
	 * @param array  where配列（参照）
	 * @param array  cond配列（参照）
	 * @param string 年月
	 * @param string fromカラム名
	 * @return 無し
	 */
	static public function setDateYM(&$where,&$cond,$search,$ymColumn,$column) {
		if( empty($search[$ymColumn]) ) return;
		$date = $search[$ymColumn].'-01';
		if( !strptime($date, '%Y-%m-%d') ) return;
		$tableName = static::TABLE_NAME;
		$fromDt = date('Y-m-d',strtotime($date));
		$toDt   = date('Y-m-d',strtotime($date . " +1 month"));
		$where[]    = "{$tableName}.{$column}>=:{$column}_FROM";
		$cond["{$column}_FROM"] = $fromDt;
		$where[]    = "{$tableName}.{$column}<:{$column}_TO";
		$cond["{$column}_TO"] = $toDt;
		return;
	}

	/**
	 * 年指定条件
	 * @param array  where配列（参照）
	 * @param array  cond配列（参照）
	 * @param string 年月
	 * @param string fromカラム名
	 * @return 無し
	 */
	static public function setDateY(&$where,&$cond,$search,$yColumn,$column) {
		if( empty($search[$yColumn]) ) return;
		$date = $search[$yColumn].'-01-01';
		if( !strptime($date, '%Y-%m-%d') ) return;
		$tableName = static::TABLE_NAME;
		$fromDt = date('Y-m-d',strtotime($date));
		$toDt   = date('Y-m-d',strtotime($date . " +1 year"));
		$where[]    = "{$tableName}.{$column}>=:{$column}_FROM";
		$cond["{$column}_FROM"] = $fromDt;
		$where[]    = "{$tableName}.{$column}<:{$column}_TO";
		$cond["{$column}_TO"] = $toDt;
		return;
	}

	/**
	 * キーワード検索条件
	 * @param array  where配列（参照）
	 * @param array  cond配列（参照）
	 * @param array  検索させるカラム名のリスト
	 * @param string スペース区切りキーワード
	 * @return 無し
	 */
	static public function setKeyWord(&$where,&$cond,$search,$column,$columnList) {
		if( !is_array($search) ) return;
		// キーワード検索
		$keyWord = trim($search[$column]);
		if( empty($keyWord) ) return;
		$tableName = static::TABLE_NAME;
		// カラムの作成
		$clist = array();
		foreach($columnList as $column) {
			if( strpos($column,'.') === FALSE ) {
				$clist[] = "IFNULL({$tableName}.{$column},'')";
			} else {
				$clist[] = "IFNULL({$column},'')";
			}
		}
		$columns = join(",",$clist);
		// キーワードの作成
		$keyWord = str_replace("　"," ",$keyWord);
		$kws = explode(" ",$keyWord);
		$no = 1;
		foreach($kws as $word) {
			$kws = mb_convert_kana($str, "as", "UTF-8"); // 英数字は半角に変換
			//$where[] = "concat($columns) like :KEYWORD{$no} COLLATE utf8_unicode_ci";
			$where[] = "concat({$columns}) like :KEYWORD{$no} COLLATE utf8mb4_unicode_ci";
			$cond["KEYWORD{$no}"] = "%{$word}%";
			$no++;
		}
		return;
	}

	/**
	 * 重複を省いたリストを返す
	 * @param array  条件配列orプライマリーキー
	 * @param string ソート条件
	 * @param limit  リミット条件
	 * @return array $list=レコード
	 */
	static public function getDistinctList($search = array(),$order = "",$limit = "",$column = "*") {
		$tableName = static::TABLE_NAME;
		$db = new cDbExt();
		list($where,$cond) = static::getWhere($search); // 検索条件の作成
		$addWhere = "";
		if( !empty($where) ) {
			$addWhere = join(" and " , $where);
		}
		if( !empty($limit) ) {
			$limit = "limit " . $limit;
		}
		if( !empty($order) ) {
			$order = "order by " . $order;
		}
		if( is_array($column) ) {
			$column = join(',',$column);
		}
		$tableName = static::TABLE_NAME;
		$sql = "select distinct {$tableName}.{$column} from {$tableName} where {$addWhere} {$order} {$limit}";
		$list = $db->getList($sql,$cond);
		return $list;
	}

	/**
	 * データ物理削除
	 * @param array  条件配列orプライマリーキー
	 * @return boolean true=OK false=NG
	 */
	static public function deleteData($search) {
		$db = new cDbExt();
		list($where,$cond) = static::getWhere($search); // 検索条件の作成
		$addWhere = "";
		if( !empty($where) ) {
			$addWhere = join(" and " , $where);
		}
		$tableName = static::TABLE_NAME;
		list($attachTable,$joinWhere) = self::getAttachTable();
		$sql = "delete from {$tableName} {$attachTable} where {$joinWhere} {$addWhere}";
		$data = $db->delete($sql,$cond);
		return true;
	}

}
?>
