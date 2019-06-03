<?PHP
/**
 * 全サイト共通ファイルインクルード
 *
 * php処理の当初でインクルードすること
 *
 * @access public
 * @author Atsushi Taguchi
 * @copyright Copyright (c) 2016, Tryit
 * @version 1.00
 * @since 2016/09/29
 */
/**
 */
// 基本クラス読み込み
require("iConfig.php");             // システムコンフィギュレーション
require("lib/ClassLoader.php");     // クラスオートローダー
require("iFunction.php");           // 共通ファンクション
if( empty($sessionDisable) ) {
	require("iSession.php");            // セッションハンドル
}
require("iDefines.php");            // 基礎定数定義ファイル
require("iMaster.php");             // 汎用マスターデータ配列定義ファイル
require("iProcessStart.php");       // システム共通プロセス開始処理
// 開発テストIP
function isTestIp() {
	if( $_SERVER['REMOTE_ADDR'] == '192.168.0.133'
	 || $_SERVER['REMOTE_ADDR'] == '118.238.203.22'
	 || $_SERVER['REMOTE_ADDR'] == '118.241.119.144'
	  ) {
		return TRUE;
	}
	return FALSE;
}
date_default_timezone_set('Asia/Tokyo');
?>
