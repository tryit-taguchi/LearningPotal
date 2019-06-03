<?PHP
/**
 * データベースアクセスページネーション対応クラス for MySQL(PDO版)
 *
 * HTMLでのページネーション用データを生成するクラス
 * 表示部分である cHtml::pagination() と組み合わせて使用します。
 *
 * <pre>
 * *********************************************************
 * 　使い方
 * *********************************************************
 * 
 * cDbExtを継承しています
 * 
 * $db = new cDbExt();
 * $list = $db->getList("select SQL_CALC_FOUND_ROWS * from TABLE where a=b");
 * // ページネーションデータの取得
 * $page = $db->getPageInfo();
 * // 表示するHTMLで次の関数をコールする
 * cHtml::pagination($page); // ページネーションバーの出力
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
// include_once("cDbExt.php");
class cDbExtPagination extends cDbExt
{
	const defPageMax = 10; // 同時に表示されるページ数
	const defListMax = 10; // 同時に表示されるレコード数
	private $listMax;
	private $pageData;     // ページデータ
	private $totalCount;   // 検索された全件数
	/**
	 * コンストラクタ
	 */
	function __construct($dbHost = null,$dbName = null,$dbUser = null,$dbPass = null) {
	    parent::__construct($dbHost,$dbName,$dbUser,$dbPass);
	}
	/**
	 * リストの取得
	 * ※SQLはSQL_CALC_FOUND_ROWSを使っていること
	 * @param string SQL（ソート条件・リミット条件を含まない事・SQL_CALC_FOUND_ROWSが入っていない場合は自動付加する）
	 * @param string 検索条件配列
	 * @param string ソート条件
	 * @param integer 開始レコード位置
	 * @param integer 同時表示最大レコード数
	 * @param integer 同時表示最大ページ数
	 * @param integer トータル行数（あらかじめ取得できる行数がわかってる場合はSQL_CALC_FOUND_ROWSしないで高速化する）
	 * @return array 取得データ配列のリスト
	 */
	public function getList($sql,$cond = null,$sort = null,$offset = null,$listMax = self::defListMax,$pageMax = self::defPageMax,$totalCount = null) {
		$this->listMax = $listMax;
		$this->pageMax = $pageMax;
		// SQL_CALC_FOUND_ROWSが指定されていなかったらクエリに追加する
		$sql = trim($sql);
		if( empty($totalCount) ) {
			$spos = stripos($sql,'SQL_CALC_FOUND_ROWS');
			if( $spos === FALSE ) {
				$sql = substr_replace($sql, "select SQL_CALC_FOUND_ROWS ", 0,strlen('select'));
			}
		}
		// ソート指定時はソート条件をSQLに付け足す
		if( !empty($sort) ) {
			$sql .= " order by {$sort}";
		}
		$resql = $sql;
		// レコード開始位置・最大件数をSQLに付け足すoffset/limit
		if( !empty($listMax) ) {
			if( !empty($offset) )
				$sql .= " limit {$offset},{$listMax}";
			else
				$sql .= " limit {$listMax}";
		}
		// クエリ実行
		$data = parent::getList($sql,$cond);
		// 一件も取得できない場合はオフセットが残っている場合があるので、０行目から再取得する
		if( empty($data) ) {
			$offset = 0;
			$sql = $resql. " limit {$listMax}";
			$data = parent::getList($sql,$cond);
		}

		if( empty($totalCount) ) {
			$this->totalCount = (int)$this->getFoundRowCount();
		} else {
			$this->totalCount = $totalCount;
		}
		// ページングデータを生成
		$this->pageData = $this->createPageData($offset,$this->totalCount,$listMax,$pageMax);
		return $data;
	}
	/**
	 * 総取得件数の取得(limitに左右されない件数)
	 * @param 無し
	 * @return integer クエリによるlimitされないレコード取得数
	 */
	public function getTotalCount() {
		return $this->totalCount;
	}
	/**
	 * ページネーション用情報データの取得
	 * @param 無し
	 * @return object ページネーション情報
	 */
	public function getPageInfo() {
		$pages = new stdClass;
		$pages->pageData   = $this->pageData;    // リスト表示用配列
		$pages->listMax    = $this->listMax;    // １ページの最大表示件数
		$pages->pageMax    = $this->pageMax;    // 最大表示ページ数
		$pages->totalCount = $this->totalCount; // 検索された全件数
		// カレントページのナンバー抽出
		$pages->firstCheck = 0;
		$pages->currentNum = 0;
		foreach((array)$pages->pageData as $no=>$data){
			$pages->firstCheck += $data->currentFlg;
			if( $data->currentFlg==1 ) $pages->currentNum = $data->listNum;
		}
		// 前後のページ番号の抽出
		$beforeNum = $pages->currentNum-$pages->listMax;
		$beforeNum = ($beforeNum<0)?0:$beforeNum;
		$maxNum    = $pages->pageData[count($pages->pageData)-1]->listNum;
		$afterNum  = $pages->currentNum+$pages->listMax;
		$afterNum  = ($afterNum>$maxNum)?$maxNum:$afterNum;
		$pages->beforeNum = $beforeNum;         // 前のページ番号
		$pages->maxNum    = $maxNum;            // 最大ページ
		$pages->afterNum  = $afterNum;          // 次のページ番号
		$pages->firstNum  = 0;
		$pages->lastNum   = (int)($pages->totalCount/$pages->listMax)*$pages->listMax;

		// 「最初」に行けるか？
		if( $pages->currentNum > 0 )
			$pages->firstEnable = 1;
		else
			$pages->firstEnable = 0;
		// 「前へ」に行けるか？
		if( $pages->currentNum > 0 )
			$pages->beforeEnable = 1;
		else 
			$pages->beforeEnable = 0;
		// 「次へ」に行けるか？
		if( $pages->currentNum+$pages->listMax <= $pages->maxNum )
			$pages->afterEnable = 1;
		 else 
			$pages->afterEnable = 0;
		// 「最後」に行けるか？
		if( $pages->currentNum < $pages->totalCount-$pages->listMax )
			$pages->lastEnable = 1;
		else
			$pages->lastEnable = 0;
		return $pages;
	}
	/**
	 * ページネーション用情報データの生成(Google型)
	 * @param integer 表示件数
	 * @param integer 全件数
	 * @param integer 表示件数
	 * @param integer 同時表示ページリンク数
	 * @return object ページネーション用基礎データ
	 */
	private function createPageData($num,$allCnt,$listMax,$pageMax = self::defPageMax) {
		if( (int)$listMax == 0 ) $listMax = 1;
		$pNow = ($num == 0 ? 1:floor($num / $listMax) + 1);		//現在ページ
		$pMax = ($allCnt == 0 ? 1:ceil($allCnt / $listMax));		//最大ページ
		//表示開始ページ
		$pCenter = floor($pageMax / 2);					//ページの中心線
		if($pMax < $pageMax) {							//表示数よりページ数が少ない
			$pSta = 1;
		}elseif($pNow - $pCenter < 1) {					//現在ページが0に近い
			$pSta = 1;
		}elseif($pNow - $pCenter + $pageMax > $pMax) {	//現在ページが終わりに近い
			$pSta = $pMax - $pageMax + 1;
		}else{											//通常
			$pSta = $pNow - $pCenter;
		}
		//表示終了ページ
		$pEnd = ($pSta + $pageMax - 1) > $pMax ? $pMax:($pSta + $pageMax - 1);

		$pPlus = $pSta;
		while($pEnd >= $pPlus) {
			$data = new stdClass;
			$data->listNum  = ($pPlus-1) * $listMax;
			$data->pageNum  = $pPlus;
			$data->currentFlg = ($num == (($pPlus-1) * $listMax) ? 1:0);
			$ret[] = $data;
			$pPlus++;
			if($pPlus > $pMax) break;
		}
		return $ret;
	}
}
?>