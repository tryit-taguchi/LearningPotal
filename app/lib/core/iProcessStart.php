<?PHP
/**
 * PHPの共通開始処理
 *
 * <pre>
 * *****************************************************************
 * この処理を通すことでできること
 * *****************************************************************
 * １．外部から引き渡されるデータが安全に取得できます。
 * 　外部データの取得方法
 * 　$REQUEST->get['NAME'];     // getで渡されるパラメータの連想配列
 * 　$REQUEST->post['NAME'];    // postで渡されるパラメータの連想配列
 * 　$REQUEST->session['NAME']; // セッションで渡されるパラメータの連想配列
 * 　$REQUEST->cookie['NAME'];  // cookieで渡されるパラメータの連想配列
 * 　$REQUEST->val['NAME'];     // 上記４種統合で渡されるパラメータの連想配列（優先順位 get>post>session>cookie）
 * ２．ＤＢの自動トランザクション開始・コミット処理
 * 　エラーによる終了(die等)の場合は自動でロールバックされます。
 * ３．エラー処理のコントロール
 * 　エラー時の処理を定義しています。
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
//------------------------------------------------------------------
// 処理開始時のプレ処理
//------------------------------------------------------------------
// 外部データの取得方法
// $REQUEST->get['NAME'];     // getで渡されるパラメータの連想配列
// $REQUEST->post['NAME'];    // postで渡されるパラメータの連想配列
// $REQUEST->session['NAME']; // セッションで渡されるパラメータの連想配列
// $REQUEST->cookie['NAME'];  // cookieで渡されるパラメータの連想配列
// $REQUEST->val['NAME'];     // 上記４種統合で渡されるパラメータの連想配列（優先順位 get>post>session>cookie）
function phpStart() {
	global $REQUEST,$DB;
	// 時間計測開始
	watchTimeStart();
	// 外部パラメータの取得
	$REQUEST   = new cRequestParameter();
	// データベースコネクション開始
	$DB        = new cDbExt();
	$DB->beginTransaction(); // トランザクション開始
	// 戻り先URLの記録
	$httpReferer = '';
	if( isset($_SERVER['HTTP_REFERER']) ) {
		$httpReferer = $_SERVER['HTTP_REFERER'];
	}
	if( strpos($httpReferer,$_SERVER["SERVER_NAME"].$_SERVER['PHP_SELF']) === FALSE ) {
		$_SESSION['backUrl'] = $httpReferer;
	}
	// アクセスがロードバランサーやプロキシだったらリモートホストを書き換える
	if( !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ) {
		$_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
}
phpStart(); // 処理開始
//------------------------------------------------------------------
// 処理完了時用の処理
//------------------------------------------------------------------
// php正常完了時に実行する関数
function phpSucsessExit() {
	global $DB;
	$DB->commit(); // 正常完了時のみDBをコミットさせる
}
register_shutdown_function("phpSucsessExit");

//------------------------------------------------------------------
// phpエラー時用の処理
//------------------------------------------------------------------
// http://php.net/manual/ja/function.set-error-handler.php
// http://www.php.net/manual/ja/errorfunc.constants.php
function errorHandler ($errno, $errstr, $errfile, $errline,$errcontext) {
	global $DB;
	// $errno は error_reporting の値を参照。
	//if( ($errno & (E_ERROR | E_WARNING | E_PARSE)) != 0 ){
	if( ($errno & ( E_ERROR | E_PARSE)) != 0 ) {
		/*
		echo "errno:" . $errno . "<br />";
		echo "errstr:".$errstr . "<br />";
		echo "errfile:".$errfile . "<br />";
		echo "errline:".$errline . "<br />";
		echo "errcontext:".$errcontext . "<br />";
		*/
		$DB->rollBack(); // エラーがあった場合はDBをロールバックさせる
		//var_dump($errcontext);
		//$errcontext2 = $errcontext;
		$errleType = array();
		if( $errno & E_ERROR > 0   ) $errleType[] = "E_ERROR";
		//if( $errno & E_WARNING > 0 ) $errleType[] = "E_WARNING";
		if( $errno & E_PARSE > 0   ) $errleType[] = "E_PARSE";
		$errorMess = sprintf("error_type:%s\n%s\n%s\n%s\nline:%d\n",join("/",(array)$errleType),$_SERVER['PHP_SELF'],$errorStr,$errfile,$errline);
		fLog($errorMes);
		if( DEBUG_MODE == FALSE ) {
			Header("Location: /nsp/");
			exit;
		} else {
			echo "<pre>";
			echo $errorMess . "\n";
			debug_print_backtrace();
			echo "</pre>";
			die();
		}
	}
	if( ($errno & (E_WARNING )) != 0 ){
		//$errorMess = sprintf("warning_type:%s\n%s\n%s\n%s\nline:%d\n",join("/",(array)$errleType),$_SERVER['PHP_SELF'],$errorStr,$errfile,$errline);
	}
}
set_error_handler("errorHandler");

?>
