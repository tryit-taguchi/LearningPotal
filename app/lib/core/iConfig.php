<?PHP
/**
 * サイト全体の共通定義ファイル
 *
 * @access public
 * @author Atsushi Taguchi
 * @copyright Copyright (c) 2013, Tryit
 * @version 1.00
 * @since 2013/07/01
 */
// php基本設定
//mb_language("Japanese");
//mb_internal_encoding("UTF-8");
//setlocale(LC_ALL,'ja_JP');
//date_default_timezone_set('Asia/Tokyo'); // タイムゾーンは東京を指定


error_reporting(E_ALL & ~E_NOTICE);
$_SERVER_TYPE = "DEVELOP";  // PRODUCT
// サーバによって設定分け
if( !empty($_SERVER['SERVER_NAME']) ) {
	switch($_SERVER['SERVER_NAME']) {
		// 開発環境
		case "192.168.0.5":
		case "nissan.t4u.bz":
		case "cmc.t4u.bz":
			$_SERVER_TYPE = "DEVELOP";
			break;
		// 本番環境
		case "localhost":
		case "127.0.0.1":
		case "192.168.11.5":
		case "192.168.11.7":
			$_SERVER_TYPE = "PRODUCT";
			break;
		case "":
			// コマンドモード/ＣＬＩの場合
			define("EXEC_TYPE","CLI");			// Cliモード動作
			if( iConfig_chkIP("192.168.0.") ) {
				$_SERVER_TYPE = "DEVELOP";  // セグメントが192.168.0. だったら開発
			} else {
				$_SERVER_TYPE = "PRODUCT";
			}
			break;
		// デフォルト（判定できない場合）
		default;
			echo "(error) {$_SERVER['SERVER_NAME']} は、iConfigに登録されていないドメインです。";
			break;
	}
}

//------------------------------------------------------------------
// URLチェック
//------------------------------------------------------------------
// XSS対策
if( strpos(strtolower($_SERVER['PHP_SELF']),'script') !== FALSE ) {
	exit("error...");
}
// 管理ページかどうか
if( strpos(strtolower($_SERVER['PHP_SELF']),'/kanri/') !== FALSE ) {
	define("ADMIN_PAGE",true);                      // 管理ページ
} else {
	define("ADMIN_PAGE",false);                      // 管理ページ
}
//------------------------------------------------------------------
// サーバ共通の定数指定
//------------------------------------------------------------------
// ＵＲＬ・パス系の設定
define("SELF_HOST",$_SERVER["SERVER_NAME"]);        // ホスト
define("SELF_DIR",dirname($_SERVER['PHP_SELF']));   // カレントディレクトリ
define("SELF_FILE",basename($_SERVER['PHP_SELF'])); // カレントファイル
define("SELF_CUR",SELF_HOST . SELF_DIR);            // ROOT
define("SELF_PATH",$_SERVER['PHP_SELF']);
define("FORM_KEEP",SELF_DIR."/".SELF_FILE);         // フォームの保持名称
//define("DOMAIN",SELF_HOST);
define("HTTP_TOP","https://" . SELF_HOST);
define("HTTP_CUR","https://" . SELF_CUR);
define("SERVER_TYPE",$_SERVER_TYPE);                // サーバタイプ
define("DEFAULT_LIST_COUNT",20);                     // 1ページの表示件数のデフォルト値
define("TAX",1.08);  // 消費税

if( strpos($_SERVER['SERVER_SOFTWARE'],'Win') !== FALSE ) {
	// Windowsだった場合
	$systemRoot = str_replace('\lib\core','',dirname(__FILE__));
	define("SYSTEM_ROOT",$systemRoot);                          // システムルートディレクトリ
	define("DOCUMENT_ROOT",str_replace('\app','',$systemRoot)); // ドキュメントルート
	define("LOG_FILE",$systemRoot.'\log\log_'.date("Y-m-d").".log"); // fLog関数を使った時のログファイル名（空文字だと出力しない）
	define("SERVER_OS","WINDOWS"); // サーバーOS
} else {
	// Linuxだった場合
	$systemRoot = str_replace('/lib/core','',dirname(__FILE__));
	$systemRoot = str_replace('\lib\core','',$systemRoot);
	define("SYSTEM_ROOT",$systemRoot);                          // システムルートディレクトリ
	define("DOCUMENT_ROOT",str_replace('/app','',$systemRoot)); // ドキュメントルート
	define("LOG_FILE",$systemRoot.'/log/log_'.date("Y-m-d").".log"); // fLog関数を使った時のログファイル名（空文字だと出力しない）
	define("SERVER_OS","LINUX"); // サーバーOS
}
define("DOMAIN",$_SERVER['SERVER_ADDR']);
define("TOP_URL","http://".DOMAIN);

//------------------------------------------------------------------
// 開発サーバでの設定
//------------------------------------------------------------------
if( $_SERVER_TYPE == "DEVELOP" )
{
	define("DEBUG_MODE",TRUE);                      // デバッグモードON
	define("ENTRY_DB_HOST","localhost");            // DBホストIP（データ）
	define("ENTRY_DB_NAME","learningportal_db");    // DB名
	define("ENTRY_DB_USER","learningportal_user");  // DBユーザー
	define("ENTRY_DB_PASS","learningportal_pass");  // DBPASS
	define("ADMIN_MAIL","nsp@t4u.bz");              // 管理者宛てメール送信先（デフォルト値）
	define("ADMIN_FROM","nsp@t4u.bz");              // 送信元メールアドレス（デフォルト値）
	define("ADMIN_NAME","NSP（開発）");             // 送信元メール名（デフォルト値）
}//\xampp\htdocs\nlp\app\log\

//------------------------------------------------------------------
// 本番サーバでの設定
//------------------------------------------------------------------
if( $_SERVER_TYPE == "PRODUCT" )
{
	define("DEBUG_MODE",FALSE);                     // デバッグモードOFF
	define("ENTRY_DB_HOST","localhost");            // DBホストIP（データ）
	define("ENTRY_DB_NAME","learningportal_db");    // DB名
	define("ENTRY_DB_USER","learningportal_user");  // DBユーザー
	define("ENTRY_DB_PASS","learningportal_pass");  // DBPASS
	define("ADMIN_MAIL","nsp@t4u.bz");              // 管理者宛てメール送信先（デフォルト値）
	define("ADMIN_FROM","nsp@t4u.bz");              // 送信元メールアドレス（デフォルト値）
	define("ADMIN_NAME","NSP（本番）");             // 送信元メール名（デフォルト値）
}

//------------------------------------------------------------------
// BASIC認証
//------------------------------------------------------------------
header('Content-Type: text/html; charset=utf-8');

// サーバのＩＰアドレスチェック
function iConfig_chkIP($ip) {
	exec(escapeshellcmd("/sbin/ifconfig"),$ifconfigs);
	$ips = array();
	foreach( (array)$ifconfigs as $key => $otext) {
		if( strpos($otext,"inet ") !== FALSE ) {
			$stmp = explode(" ",str_replace("inet ","",trim($otext)));
			$ips[] = $stmp[0];
		}
	}
	$_iConfig_hostIpAddress = join(" ",$ips);
	if( strpos($_iConfig_hostIpAddress,$ip) !== FALSE ) {
		return TRUE;
	} else {
		return FALSE;
	}
}
?>
