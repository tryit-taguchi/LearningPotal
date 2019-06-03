<?PHP
/**
 * セッション制御
 *
 * @access public
 * @author Atsushi Taguchi
 * @copyright Copyright (c) 2013, Tryit
 * @version 1.00
 * @since 2013/07/01
 */
/**
 */
//$_SESSION[] = array();	// セッションを保持する配列

// セッションのハンドラ関数を登録
header("Cache-Control: no-cache");
header("Pragma: no-cache");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
// クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');
session_cache_limiter('private_no_expire');
session_start();
/* IE用クロスドメイン対策ヘッダー追加 */
header("P3P: CP='UNI CUR OUR'");
//$_SESSION['lastaccess'] = date("H:i:s");
?>
