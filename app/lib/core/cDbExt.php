<?PHP
/**
 * データベースアクセス拡張クラス(PDO版)
 *
 * データベースの基本機能にデータアクセスのラッピングをしたクラス
 * テーブルの作成、データの登録、更新、削除、検索ができる。
 *
 * <pre>
 * *********************************************************
 * 　使い方
 * *********************************************************
 * ■SELECT文例
 * $db = new cDbExt();
 * ・直値指定
 * $db->select("select * from TABLE_NAME where TABLE_ID = 1234");
 * print_r($db->getResult());
 * ・プリペアドステートメント対応指定
 * $cond['TABLE_ID'] = 1234;
 * $db->select("select * from TABLE_NAME where TABLE_ID = :TABLE_ID",$cond);
 * print_r($db->getResult());
 * ・配列でレコードを返す
 * $cond['TABLE_ID'] = 1234;
 * print_r($db->getList("select * from TABLE_NAME where TABLE_ID = :TABLE_ID",$cond));
 * ・レコードの先頭１つを返す
 * $cond['TABLE_ID'] = 1234;
 * print_r($db->getData("select * from TABLE_NAME where TABLE_ID = :TABLE_ID",$cond));
 *
 * ■INSERT例文（このクラスを使う場合はテーブルの判定をしてるので使用するテーブルに最低限１レコード入ってること）
 * $data['PARAM_NAME'] = 'test';
 * $data['INS_DATE'] = cDate::now();
 * $db->setInsertTable("TABLE_NAME");
 * $db->setInsertList($data); // 配列はテーブルカラム名と同じ連想配列に基づいたリストにすること
 * $db->insertExec();
 *
 * ■UPDATE例文（このクラスを使う場合はテーブルの判定をしてるので使用するテーブルに最低限１レコード入ってること）
 * $db->setUpdateTable("TABLE_NAME");
 * $db->setUpdateList($data); // 配列はテーブルカラム名と同じ連想配列に基づいたリストにすること
 * $cond['TABLE_ID'] = 2120;
 * ・イコールで連装配列と結びつけるだけのシンプルな場合
 * $db->setUpdateWhere($cond);
 * ・条件がイコール以外の場合
 * $db->setUpdateWhere("TABLE_ID >= :TABLE_ID",$cond);
 * ・直値指定の場合
 * $db->setUpdateWhere("TABLE_ID = 1234");
 * $db->updateExec();
 *
 * ■REPLACE例文（このクラスを使う場合はテーブルの判定をしてるので使用するテーブルに最低限１レコード入ってること）
 * リプレース条件はMySQLマニュアル参照の事
 * $data['PARAM_NAME'] = 'test';
 * $data['UPD_DATE'] = cDate::now();
 * $db->setReplaceTable("TABLE_NAME");
 * $db->setReplaceList($data); // 配列はテーブルカラム名と同じ連想配列に基づいたリストにすること
 * $db->replaceExec();
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
// include_once("cDb.php");
class cDbExt extends cDb
{
	//public $mysql_ver;	//MYSQLのバージョン情報
	private $params;    //パラメータの保存
	private $cond;      //WHERE文用検索パラメータの保存
	/**
	 * コンストラクタ
	 */
	function __construct($dbHost = null,$dbName = null,$dbUser = null,$dbPass = null) {
	    parent::__construct($dbHost,$dbName,$dbUser,$dbPass);
		//$this->mysql_ver = mysql_get_server_info();
		//$this->mysql_ver = $this->dbh->server_info;
		//echo $this->mysql_ver;
	}

	/**
	 * ＤＢのリストを配列に収納
	 * （プリペアドステートメントの差し込み文字と配列のキーが同じであること）
	 * @param string SELECTクエリ
	 * @param array  パラメータ配列
	 * @return array 取得データ配列のリスト
	 */
	public function getList($qsql,$cond = null) {
		$this->select($qsql,$cond);
		$this->getResult();
		if( isset($this->data) ) {
			return $this->data;
		} else {
			return array();
		}
	}

	/**
	 * ＤＢの内容を配列に収納
	 * （プリペアドステートメントの差し込み文字と配列のキーが同じであること）
	 * @param string SELECTクエリ
	 * @param array  パラメータ配列
	 * @return array 取得データ連想配列
	 */
	public function getData($qsql,$cond = null) {
		$this->select($qsql,$cond);
		$ret = null;
		if( $this->rowCount() > 0 ) {
			$ret = $this->fetch();
		}
		return $ret;
	}

	/**
	 * ＳＥＬＥＣＴ文の結果があるかどうか確認する
	 * @param string  SELECTクエリ
	 * @return bool 結果あり=TRUE 結果無し=FALSE
	 */
	public function isSelect($qsql) {
		$this->select($qsql);
		$numrow = $this->rowCount();
		if( $numrow == 0 ) return FALSE;
		else               return TRUE;
	}

	/**
	 * 直近のインサートテーブルのオートインクリメントのIDを取り出す
	 * @param 無し
	 * @return integer ラストID
	 */
	public function getLastInsertId() {
		return $this->dbh->lastInsertId();
		//$this->exec("SELECT LAST_INSERT_ID() AS last_value FROM $seq_table");
		//return $this->result( 0,"last_value");
		//return mysql_result($this->result,0,"last_value");
	}

	/**
	 * ＳＱＬのカウント情報のみ取り出し
	 * @param string SELECTクエリ
	 * @param string カウントカラム名
	 * @return integer カウント数
	 */
	public function getCount($qsql,$cntColumn = 'CNT') {
		$ret = $this->getData($qsql);
		return $ret[$cntColumn];
	}

	/**
	 * SELECT文にSQL_CALC_FOUND_ROWSを使った時のレコード数取得
	 * @param 無し
	 * @return integer 検索一致数
	 */
	public function getFoundRowCount() {
		return $this->getCount("SELECT FOUND_ROWS() as CNT");;
	}

	/**
	 *
	 * テーブル情報系メソッド
	 *
	 */
	/**
	 * フィールドの名前・型対応表を作成する
	 * @param string テーブルネーム
	 * @return array[] テーブル型情報配列
	*/
	private function getFieldTypeList($table_name) {
		$qsql = "select * from {$table_name} limit 1";
		$result = $this->dbh->prepare($qsql); // プリペアドステートメント対応
		$ret = $result->execute();
		if( !$ret ) {
			$error = $result->errorInfo();
			$errorMes = sprintf("指定のテーブルは空か存在しません。\n%s\n%d:%d: %s\n",$_qsql,$error[0],$error[1],$error[2]);
			printf($errorMes);
			exit;
		} else {
			$num = 0;
			while ($column = $result->getColumnMeta($num++)) {
				$field[$column['name']]['name'] = $column['name'];
				$field[$column['name']]['type'] = isset($column['native_type'])?$column['native_type']:'';
				$field[$column['name']]['len']  = $column['len'];
			}
			//fLog_var($field);
		}
		return $field;
	}

	/**
	 *
	 * ＳＱＬインサート拡張系
	 *
	 */
	private $insert_table;			// インサートするテーブル名
	private $insert_data;			// インサートするデータ
	private $insert_qsql;			// 生成されたＳＱＬ
	private $insert_filed_type;		// インサートをするフィールド属性
	/**
	 * インサート拡張の初期化（テーブルセット時に自動呼出し）
	 * @param void 無し
	 */
	private function insertInit() {
		unset($this->insert_table);
		unset($this->insert_qsql);
		$this->insert_filed_type = array();
		$this->insert_data = array();
		$this->params = array();
	}
	/**
	 * インサートテーブルのセッティング
	 * @param string テーブルネーム
	 */
	public function setInsertTable($table_name) {
		$this->insertInit();
		$this->insert_table = $table_name;
		$this->insert_filed_type = $this->getFieldTypeList($table_name);
	}
	/**
	 * インサートデータのセッティング
	 * @param string カラム名
	 * @param mixed 値
	 * @return bool カラムが存在=TRUE カラムが不在=FALSE
	 */
	public function setInsertData($name,$value) {
		// パラメータが空だったら抜ける
		if( isset($value) == FALSE ) return;
		if( $value === '' ) return;
		// ＤＢ側に同名のフィールドが無かったら抜ける
		if( empty($this->insert_filed_type[$name]) ) return false;
		//if( !isset($this->insert_filed_type[$name]) ) return false;
		$this->insert_data[$name] = ':'.$name;
		return true;
	}
	/**
	 * インサートデータのセッティング一括配列登録版
	 * @param array カラム名がキーになった値の配列
	 * @return void 無し
	 */
	public function setInsertList($array_data) {
		//fLog_var($array_data);
		while (list ($key, $val) = each ( $array_data )) {
			if( $this->setInsertData($key,$val) ) {
				$this->params[$key] = $this->normalize($this->insert_filed_type,$key,$val); // DBにあるカラムのみパラメータに設定する
			}
		}
	}
	/**
	 * セットされたインサート情報の実行
	 * @param 無し
	 * @return void 無し
	 */
	public function insertExec() {
		$this->insertSql();
		if( DEBUG_MODE==TRUE ) {
			$str = $this->insert_qsql;
			$str = preg_replace("/\s+/", " ",$str); // スペースの連続は一つにまとめる
			$str = preg_replace("/\t+/", " ",$str); // スペースの連続は一つにまとめる
			//fLog($str . "\n");
		}
		//fLog($this->insert_qsql . "\n");
		$this->insert($this->insert_qsql,$this->params);
	}
	/**
	 * インサート文の生成
	 * @param 無し
	 * @return string 設定された情報に基づいたクエリ
	 */
	private function insertSql() {
		while (list ($key, $val) = each ( $this->insert_data )) {
			$fields[] = $key;
			$values[] = $val;
		}
		reset($this->insert_data);
		$this->insert_qsql = "";
		$this->insert_qsql .= "INSERT INTO ".$this->insert_table." (";
		$this->insert_qsql .= join(",",$fields);
		$this->insert_qsql .= " ) VALUES ( ";
		$this->insert_qsql .= join(",",$values);
		$this->insert_qsql .= ")";
		return $this->insert_qsql;
	}

	/**
	 *
	 * ＳＱＬアップデート拡張系
	 *
	 */
	private $update_table;			// アップデートするテーブル名
	private $update_data;			// アップデートするデータ
	private $update_where;			// ＳＱＬのwhere条件
	private $update_qsql;			// 生成されたＳＱＬ
	private $update_filed_type;		// アップデートをするフィールド属性
	/**
	 * アップデート拡張の初期化（テーブルセット時に自動呼出し）
	 * @param 無し
	 */
	private function updateInit() {
		unset($this->update_table);
		unset($this->update_where);
		unset($this->update_qsql);

		$this->update_filed_type = array();
		$this->update_data = array();
		$this->params = array();
	}
	/**
	 * アップデートテーブルのセッティング
	 * @param string テーブルネーム
	 */
	public function setUpdateTable($table_name) {
		$this->updateInit();
		$this->update_table = $table_name;
		$this->update_filed_type = $this->getFieldTypeList($table_name);
	}
	/**
	 * アップデートの条件式セッティング
	 * 使い方<br>
	 * $db->setUpdateWhere(" STRINGS like '%STRINGS%'");     // whereを直値で指定<br>
	 * $db->setUpdateWhere( array('STRINGS' => '%STRINGS%')); // 配列をイコール検索<br>
	 * $db->setUpdateWhere(" STRINGS like :STRINGS",array('STRINGS' => '%STRINGS%')); // プリペアドステートメント対応<br>
	 * @param string クエリのWHERE部分
	 * @param array  パラメータ配列
	 */
	public function setUpdateWhere($where_data,$cond = null) {
		if( !is_array($where_data) ) {
			$this->cond = $cond;
			// 直値指定のwhere文
			// プリペアドステートメント対応のwhere文
			$this->update_where = $where_data;
		} else {
			// 配列で渡されたら配列の内容をイコールでアンド検索
			$this->cond = $where_data;
			$wheres = array();
			foreach($where_data as $name => $value) {
				$wheres[] = $name . " = :" . $name;
			}
			$this->update_where = join(" AND ",$wheres);
		}
	}
	/**
	 * アップデートデータのセッティング
	 * @param string カラム名
	 * @param mixed 値
	 * @return カラムが存在=TRUE カラムが不在=FALSE
	 */
	public function setUpdateData($name,$value) {
		// パラメータが空だったら抜ける
		//if( isset($value) == FALSE ) return false;
		// ＤＢ側に同名のフィールドが無かったら抜ける
		if( isset($this->update_filed_type[$name]) == FALSE ) return false;
		$this->update_data[$name] = $name . ' = :'.$name;
		return true;
	}
	/**
	 * アップデートのセッティング一括配列登録版
	 * @param array カラム名がキーになった値の配列
	 * @return void 無し
	 */
	public function setUpdateList($array_data) {
		while (list ($key, $val) = each ( $array_data )) {
			if( $this->setUpdateData($key,$val) ) {
				$this->params[$key] = $this->normalize($this->update_filed_type,$key,$val); // DBにあるカラムのみパラメータに設定する
			}
		}
	}
	/**
	 * セットされたアップデート情報の実行
	 * @param 無し
	 * @return string 実行したクエリ
	 */
	public function updateExec() {
		$this->update_qsql  = "";
		$this->update_qsql .= "UPDATE ".$this->update_table." SET \n";
		$this->update_qsql .= join(",",$this->update_data) . " \n";
		$this->update_qsql .= " WHERE  \n";
		$this->update_qsql .= $this->update_where . "\n";

		if( DEBUG_MODE==TRUE ) {
			$str = $this->update_qsql;
			$str = preg_replace("/\s+/", " ",$str); // スペースの連続は一つにまとめる
			$str = preg_replace("/\t+/", " ",$str); // スペースの連続は一つにまとめる
			//fLog($str . "\n");
		}
		/*
		echo "<pre>";
		echo $this->update_qsql;
		print_r($this->params);
		echo "</pre>";
		*/
		$this->update($this->update_qsql,$this->params,$this->cond);
		return $this->update_qsql;
	}

	/**
	 *
	 * ＳＱＬリプレース拡張系
	 *
	 */
	private $replace_table;			// リプレースするテーブル名
	private $replace_data;			// リプレースするデータ
	private $replace_qsql;			// 生成されたＳＱＬ
	private $replace_filed_type;		// リプレースをするフィールド属性
	/**
	 * リプレース拡張の初期化（テーブルセット時に自動呼出し）
	 * @param 無し
	 */
	private function replaceInit() {
		unset($this->replace_table);
		unset($this->replace_qsql);
		$this->replace_filed_type = array();
		$this->replace_data = array();
		$this->params = array();
	}
	/**
	 * リプレースデートテーブルのセッティング
	 * @param string テーブルネーム
	 */
	public function setReplaceTable($table_name) {
		$this->replaceInit();
		$this->replace_table = $table_name;
		$this->replace_filed_type = $this->getFieldTypeList($table_name);
	}
	/**
	 * リプレースデータのセッティング
	 * @param string カラム名
	 * @param mixed  値
	 * @return カラムが存在=TRUE カラムが不在=FALSE
	 */
	public function setReplaceData($name,$value) {
		// パラメータが空だったら抜ける
		if( isset($value) == FALSE ) return;
		// ＤＢ側に同名のフィールドが無かったら抜ける
		if( empty($this->replace_filed_type[$name]) ) return false;
		//if( !isset($this->replace_filed_type[$name]) ) return false;
		$this->replace_data[$name] = ':'.$name;
		return true;
	}
	/**
	 * リプレースのセッティング一括配列登録版
	 * @param array カラム名がキーになった値の配列
	 * @return void 無し
	 */
	public function setReplaceList($array_data) {
		//fLog_var($array_data);
		while (list ($key, $val) = each ( $array_data )) {
			if( $this->setReplaceData($key,$val) ) {
				$this->params[$key] = $this->normalize($this->replace_filed_type,$key,$val); // DBにあるカラムのみパラメータに設定する
			}
		}
	}
	/**
	 * セットされたリプレース情報の実行
	 * @param 無し
	 * @return string 実行したクエリ
	 */
	public function replaceExec() {
		$this->replaceSql();
		if( DEBUG_MODE==TRUE ) {
			$str = $this->replace_qsql;
			$str = preg_replace("/\s+/", " ",$str); // スペースの連続は一つにまとめる
			$str = preg_replace("/\t+/", " ",$str); // スペースの連続は一つにまとめる
			//fLog($str . "\n");
		}
		//fLog($this->replace_qsql . "\n");
		$this->replace($this->replace_qsql,$this->params);
	}
	/**
	 * リプレース文の生成
	 * @param 無し
	 * @return string 設定された情報に基づいたクエリ
	 */
	private function replaceSql() {
		while (list ($key, $val) = each ( $this->replace_data )) {
			$fields[] = $key;
			$values[] = $val;
		}
		reset($this->replace_data);
		$this->replace_qsql = "";
		$this->replace_qsql .= "REPLACE INTO ".$this->replace_table." (";
		$this->replace_qsql .= join(",",$fields);
		$this->replace_qsql .= " ) VALUES ( ";
		$this->replace_qsql .= join(",",$values);
		$this->replace_qsql .= ")";
		return $this->replace_qsql;
	}

	/**
	 * カラムの型に応じて、入ってはいけない値を無くす
	 * @param array フィールドタイプ配列
	 * @param name カラム名
	 * @param val 値
	 * @return mixed 変換された値
	 */
	private function normalize(&$filed_type,$name,$val) {
		$type = $filed_type[$name]['type'];
		if( !empty($type) == TRUE) {
			switch( $type )
			{
				case 'TINY':
				case 'SHORT':
				case 'INT24':
				case 'LONG':
				case 'LONGLONG':
				case 'FLOAT':
				case 'DOUBLE':
				case 'DECIMAL':
					if( empty($val) ) return 0;
					break;
				default:
					break;
			}
		}
		return $val;
	}
}
?>