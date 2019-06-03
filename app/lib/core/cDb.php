<?php
/**
 * データベースアクセス基本クラス for MySQL(PDO版)
 *
 * データベースアクセスへの基本機能となるクラス
 * 低階層でのデータベースアクセスを行います
 *
 * <pre>
 * *********************************************************
 * 　使い方
 * *********************************************************
 * ■クエリーの実行例
 * $db = new cDb();
 * $db->exec("select * from TABLE_NAME where TABLE_ID = 1234");
 * $db->getResult();
 * $db->data プロパティーに結果の配列が入る
 * </pre>
 * @access public
 * @author Atsushi Taguchi
 * @copyright Copyright (c) 2013, Tryit
 * @version 1.00
 * @since 2013/07/01
 */
/**
*/
$dbh = null; // PDOのコネクタオブジェクト
$dbOuterHost = null;	// ホスト外部設定
$dbOuterName = null;	// DB名外部設定
$dbOuterUser = null;	// DBユーザ外部設定
$dbOuterPass = null;	// DBパス外部設定
$dbKeepHost  = null;	// 前回接続時のホスト
$dbKeepName  = null;	// 前回接続時のDB名
class cDb
{
	public $logtxt;		// ログテキスト
	public $dbh;		// データベースコネクタ
	protected $result;	// リザルトオブジェクト用変数
	protected $data;	// リザルトデータ用変数
	protected $sql;		// ＳＱＬの記憶
	private $commitCheck;
	/**
	 * コンストラクタ
	 */
	function __construct($dbHost = null,$dbName = null,$dbUser = null,$dbPass = null) {
		global $dbh,$dbOuterHost,$dbOuterName,$dbOuterUser,$dbOuterPass,$dbKeepHost,$dbKeepName;
		if( empty($dbHost) ) {
			if( empty($dbOuterHost) ) $dbHost = ENTRY_DB_HOST;
			else                      $dbHost = $dbOuterHost;
		}
		if( empty($dbName) ) {
			if( empty($dbOuterName) ) $dbName = ENTRY_DB_NAME;
			else                      $dbName = $dbOuterName;
		}
		if( empty($dbUser) ) {
			if( empty($dbOuterUser) ) $dbUser = ENTRY_DB_USER;
			else                      $dbUser = $dbOuterUser;
		}
		if( empty($dbPass) ) {
			if( empty($dbOuterPass) ) $dbPass = ENTRY_DB_PASS;
			else                      $dbPass = $dbOuterPass;
		}
		// 前回接続時とホストが違ったら再コネクト
		if( $dbKeepHost != $dbHost ) {
			$dbh = null;
		}
		// 前回接続時とDB名が違ったら再コネクト
		if( $dbKeepName != $dbName ) {
			$dbh = null;
		}
		if( empty($dbh) ) {
			// 通常はプライマリ接続
			$dsn = 'mysql:dbname='.$dbName.';host='.$dbHost.'';
			try {
				$dbh = new PDO($dsn, $dbUser, $dbPass);
				$dbh->query("set names utf8mb4"); // UTF8にする
			} catch (PDOException $e) {
				echo "メンテナンス中です。";
				exit;
			}
		}
		$this->dbh  = $dbh;    // ２回目以降は接続済みのコネクションから取り出す
		$dbKeepHost = $dbHost; // 当回接続したホストを保存しておく
		$dbKeepName = $dbName; // 当回接続したDB名を保存しておく
	}
	/**
	 * クエリーの実行
	 * （プリペアドステートメントの差し込み文字と配列のキーが同じであること）
	 * @param string SQL
	 * @param array  設定パラメータ配列（参照の場合は検索パラメータ）
	 * @param array  検索パラメータ配列
	 * @return array 取得データ配列のリスト
	 */
	public function exec($sql,$params = null,$cond = null) {
		if( trim($sql) == '' ) {
			printf("ＳＱＬが指定されていません。変数を確認してください。\n%s\n",$sql);
			return false;
		}
		unset($this->data);
		$this->sql = $sql;

		$this->result = $this->dbh->prepare($this->sql); // プリペアドステートメント対応
		// パラメータが渡されていたら、バインドする
		if( !empty($params) ) {
			foreach($params as $cname => &$value) {
				if ($value === "") { 
					$this->result->bindValue(":".$cname, null, PDO::PARAM_NULL);
				} else {
					$this->result->bindValue(':'.$cname,$value);
				}
			}
		}
		if( !empty($cond) ) {
			foreach($cond as $cname => &$value) {
				$this->result->bindValue(':'.$cname,$value);
			}
		}
		try {
			$ret = $this->result->execute();
		} catch (PDOException $e) {
			dout($sql);
		}

		if( !$ret ) {
			$error = $this->result->errorInfo();
			$errorMes = sprintf("ＳＱＬ実行エラー\n%s\n%d:%d: %s\n",$sql,$error[0],$error[1],$error[2]);
			//$this->rollBack(); // SQLエラーがあった場合はロールバックさせる
			if( DEBUG_MODE == FALSE ) {
fLog("-START-----------------------------------------");
fLog($_SERVER["REQUEST_URI"]);
fLog($errorMes);
fLog("-END------------------------------------------");
			} else {
				dout($errorMes); // エラーを画面表示
				dout($params); // エラーを画面表示
echo "<pre>";
debug_print_backtrace();
echo "</pre>";
			}
			die();
		}
		return $this->result;
	}
	/**
	 * セレクトを行う
	 * （プリペアドステートメントの差し込み文字と配列のキーが同じであること）<br>
	 * $this->exec()のラッパー<br>
	 * @param string SQL
	 * @param array  設定パラメータ配列（参照の場合は検索パラメータ）
	 * @param array  検索パラメータ配列
	 * @return array 取得データ配列のリスト
	 */
	public function select($sql,$cond = null) {
		return $this->exec($sql,$cond);
	}
	/**
	 * インサートを行う
	 * （プリペアドステートメントの差し込み文字と配列のキーが同じであること）<br>
	 * $this->exec()のラッパー<br>
	 * @param string SQL
	 * @param array  設定パラメータ配列
	 * @return array 取得データ配列のリスト
	 */
	public function insert($sql,$data = null) {
		return $this->exec($sql,$data);
	}
	/**
	 * アップデートを行う
	 * （プリペアドステートメントの差し込み文字と配列のキーが同じであること）<br>
	 * $this->exec()のラッパー<br>
	 * @param string SQL
	 * @param array  設定パラメータ配列
	 * @param array  検索パラメータ配列
	 * @return array 取得データ配列のリスト
	 */
	public function update($sql,$data = null,$cond = null) {
		return $this->exec($sql,$data,$cond);
	}
	/**
	 * デリートを行う
	 * （プリペアドステートメントの差し込み文字と配列のキーが同じであること）<br>
	 * $this->exec()のラッパー<br>
	 * @param string SQL
	 * @param array  検索パラメータ配列
	 * @return array 取得データ配列のリスト
	 */
	public function delete($sql,$cond = null) {
		return $this->exec($sql,$cond);
	}
	/**
	 * デリートを行う
	 * （プリペアドステートメントの差し込み文字と配列のキーが同じであること）<br>
	 * $this->exec()のラッパー<br>
	 * @param string SQL
	 * @param array  設定パラメータ配列
	 * @return array 取得データ配列のリスト
	 */
	public function replace($sql,$data = null) {
		return $this->exec($sql,$data);
	}
	/**
	 * エラーログ文字列をセット
	 * @param string メッセージ文字列
	 * @return void 無し
	 */
	private function set_loqtxt($txt) {
		$this->logtxt .= $txt;
	}
	/**
	 * クエリーの実行後の行数を返す
	 * @param 無し
	 * @return integer カウント数
	 */
	public function rowCount() {
		return $this->result->rowCount();
	}
	/**
	 * 結果を取得する
	 * @param 無し
	 * @return array データ配列（２次元）
	 */
	public function getResult() {
		if( empty($this->data) ) {
			if( $this->rowCount() == 0 ) return array();
			while ($row = $this->fetch() ) {
				$this->data[] = $row;
			}
		}
		return $this->data;
	}
	/**
	 * 行を１行取得する
	 * @param 無し
	 * @return array データ配列（１次元）
	 */
	public function fetch() {
		return $this->result->fetch(PDO::FETCH_ASSOC);
	}
	/**
	 * クエリーの結果を返す
	 * @param integer 結果のレコード番号
	 * @param string カラム名
	 * @return string 収納値
	 */
	public function result($_row,$_cname) {
		$this->getResult();
		return $this->data[$_row][$_cname];
	}
	/**
	 * カラム数を返す
	 * @param  無し
	 * @return integer テーブルのカラム数
	 */
	public function numfields() {
		return $this->dbh->field_count;
	}
	/**
	 * ＤＢクローズ
	 * @param  無し
	 * @return void 無し
	 */
	public function close() {
		$this->dbh->close();
	}
	/**
	 * インサート後の最後のシーケンス番号の取り出し
	 * @param  無し
	 * @return integer シーケンス番号
	 */
	public function getseq() {
		return $this->dbh->lastInsertId();
	}
	/**
	 * トランザクション開始
	 * @param  無し
	 * @return bool 成否
	 */
	public function beginTransaction() {
		$this->commitCheck = true;
		return $this->dbh->beginTransaction();
	}
	/**
	 * トランザクションコミット
	 * @param  無し
	 * @return bool 成否
	 */
	public function commit() {
		if( $this->commitCheck == true ) {
			return $this->dbh->commit();
		}
	}
	/**
	 * トランザクションロールバック
	 * @param  無し
	 * @return bool 成否
	 */
	public function rollBack() {
		$this->commitCheck = false;
		return $this->dbh->rollBack();
	}
}
?>
