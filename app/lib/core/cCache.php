<?PHP
/**
 * キャッシュファイル生成用クラス
 * 計算済みの値等をキャッシュファイル化しておきます
 * 
 * @access public
 * @author Atsushi Taguchi
 * @copyright Copyright (c) 2013, Tryit
 * @version 1.00
 * @since 2013/07/01
 */
/**
 */
class cCache
{
	/**
	 * コンストラクタ
	 */
	function __construct() {

	}

	/**
	 * キャッシュデータへの書き込み
	 * @param key
	 * @array 配列データ
	 * @return boolean TRUE=成功 FALSE=失敗
	 */
	static public function write($key,$data) {
		if( is_object($data) || is_array($data) ) {
			$data = serialize($data);
		}
		apc_delete($key);
		apc_add($key,$data);
		return true;
	}

	/**
	 * キャッシュデータの読み込み
	 * @param key
	 * @return boolean array=データ FALSE=失敗
	 */
	static public function read($key) {
		$data = apc_fetch($key);
		$obj = unserialize($data);
		if( $obj == false ) {
			return $data;
		} else {
			return $obj;
		}
	}

	/**
	 * キャッシュデータ削除
	 * @param key
	 * @array 配列データ
	 * @return boolean TRUE=成功 FALSE=失敗
	 */
	static public function delete($key) {
		apc_delete($key);
		return true;
	}

	/**
	 * キャッシュを全てクリア
	 * @param 無し
	 * @return boolean TRUE=成功 FALSE=失敗
	 */
	static public function allClear() {
		apc_clear_cache('user');
		return true;
	}

	static public function htmlMinify($includeName) {
		$minifyFile   = $includeName.'.min';
		$minifyFlashFlg = false;
		if( file_exists($minifyFile) ) {
			// ファイルが存在したら時間比較
			$srcTime = filemtime($includeName);
			$minTime = filemtime($minifyFile);
			if( $srcTime > $minTime ) $minifyFlashFlg = true;
		} else {
			// ファイルが存在しなかったらminifyファイルをフラッシュする
			$minifyFlashFlg = true;
		}
		// ファイルが無かったか、ファイルが更新されていたらminifyして保存
		if( $minifyFlashFlg == true ) {
			file_put_contents($minifyFile,self::minify(file_get_contents($includeName)));
		}
		// minifyしたファイルパスを返す
		return $minifyFile;
	}

}
?>
