<?PHP
/**
 * 汎用関数
 *
 * @access public
 * @author Atsushi Taguchi
 * @copyright Copyright (c) 2013, Tryit
 * @version 1.00
 * @since 2013/07/01
 */
/**
 */
/**
 * ログファイルに記録
 * @param string  記録文字列
 * @return void 無し
 */
function fLog($str,$prg = "") {
	if( LOG_FILE == "" ) return;
	if( is_array($str) || is_object($str) ) {
		fLog_var($str,$prg);
		return;
	}
	$fp = fopen(LOG_FILE, "a");
	$attr = date("Y-m-d H:i:s") . " / ";
	if( !empty($_SERVER["REMOTE_ADDR"]) ) {
		$attr .= $_SERVER["REMOTE_ADDR"] . " / ";
	}
	if( !empty($prg) ) {
		$attr .= $prg . " / ";
	}
	$line = explode("\n",$str);
	foreach($line as $str) {
		if( SERVER_OS == "WINDOWS" ) {
			$str = mb_convert_encoding($str, "SJIS-win", "auto");
		}
		fputs($fp,$attr .  $str . "\n");
	}
	fclose($fp);
}
/**
 * ログファイルに記録（配列出力版）
 * @param array 配列
 * @return void 無し
 */
function fLog_var($data,$prg = "") {
	if( LOG_FILE == "" ) return;
	$fp = fopen(LOG_FILE, "a");
	$attr = date("Y-m-d H:i:s") . " / ";
	if( !empty($_SERVER["REMOTE_ADDR"]) ) {
		$attr .= $_SERVER["REMOTE_ADDR"] . " / ";
	}
	if( !empty($prg) ) {
		$attr .= $prg . " / ";
	}
	$str  = var_export($data,TRUE);
	$line = explode("\n",$str);
	foreach($line as $str) {
		if( SERVER_OS == "WINDOWS" ) {
			$str = mb_convert_encoding($str, "SJIS-win", "auto");
		}
		fputs($fp,$attr .  $str . "\n");
	}
	fclose($fp);
}
/**
 * 配列の表示（デバッグ用）
 * @param array 配列
 * @return void 無し
 */
function dout($data) {
	echo "<pre>";
	print_r($data);
	echo "</pre>";
}
/**
 * サイト環境表示（デバッグ用）
 * @param void 無し
 * @return void 無し
 */
function debugDisplay() {
	if( DEBUG_MODE == TRUE ) {
		echo "<FONT color='#808080'><PRE>";
		echo "ADMIN_MAIL : " . ADMIN_MAIL . "\n";
		echo "ADMIN_FROM : " . ADMIN_FROM . "\n";
		echo "SELF_PATH : " . SELF_PATH . "\n";
		echo "HTTP_TOP : " . HTTP_TOP . "\n";
		echo "SSL_TOP : " . SSL_TOP . "\n";
		echo "HTTP_CUR : " . HTTP_CUR . "\n";
		echo "SSL_CUR : " . SSL_CUR . "\n";
		echo "SELF_DIR : " . SELF_DIR . "\n";
		print_r($_SESSION);
		echo "</PRE></FONT>";
	}
}
/**
 * メモリ使用量（チューニング用）
 * @param void 無し
 * @return メモリ量
 */
function getUseMemory() {
	$mem = memory_get_usage();
	$mem = number_format($mem);
	return $mem;
}

/**
 * 時間計測開始（チューニング用）
 * @param void 無し
 * @return void 無し
 */
$__tkeep = 0;
$__tkeep_total = 0;
function watchTimeStart() {
	global $__tkeep;
	$__tkeep = microtime();
	global $__tkeep_total;
	$__tkeep_total = microtime();
}
/**
 * 経過時間取得（チューニング用）
 * @param void 無し
 * @return void 無し
 */
function watchTimeRap() {
	global $__tkeep;
	$tmp = explode(' ',$__tkeep);
	$diff1 = $tmp[0]+$tmp[1]%3600;
	$tmp = explode(' ',microtime());
	$diff2 = $tmp[0]+$tmp[1]%3600;
	$diff = $diff2-$diff1;
	$__tkeep = microtime();
	return sprintf("%2.5f",$diff);
}
/**
 * 経過時間取得（チューニング用）
 * @param void 無し
 * @return void 無し
 */
function watchTimeTotalRap() {
	global $__tkeep_total;
	$tmp = explode(' ',$__tkeep_total);
	$diff1 = $tmp[0]+$tmp[1]%3600;
	$tmp = explode(' ',microtime());
	$diff2 = $tmp[0]+$tmp[1]%3600;
	$diff = $diff2-$diff1;
	$__tkeep_total = microtime();
	return sprintf("%2.5f",$diff);
}
/**
 * 配列のkeyをvalueに変換
 * @param array 入力配列
 * @return array 結果配列
 */
function array_key2value($ary) {
	$out = array();
	if( is_array($ary) == TRUE ) {
		while (list ($key, $value) = each ( $ary )) {
			$out[] = $key;
		}
	}
	return $out;
}
/**
 * リダイレクト
 * @param string URL
 * @return void 無し
 */
function Redirect($url)
{
	if( MB_MODE!==true ) {
		// pc等セッションがクッキーで引き継がれる場合
		Header("Location: $url");
	} else {
		// 携帯等セッションをURLで引き継がなければならない場合
		if( strpos($url,"http") === FALSE ) {
			$url = TOP_URL . "/" . $url;
		}
		if( strpos($url,"?") == FALSE ) {
			$url .= "?PHPSESSID=".session_id();
		} else {
			$url .= "&PHPSESSID=".session_id();
		}
		Header("Location: $url");
	}
	exit;
}

/**
 * クッキーのセット
 * @param string カラム名
 * @param string 値
 * @return void 無し
 */
function cookieSet($name,$value) {
//	setcookie($name,$value,time()+60*60*24*365);
	setcookie($name,$value,time()+60*60*24*365,"/");
}
/**
 * クッキーのデリート
 * @param string カラム名
 * @return void 無し
 */
function cookieDel($name)
{
//	setcookie($name,"",time()-3600*24*365);
	setcookie($name,"",time()-3600*24*365,"/");
}
/**
 * ブラウザタイプ取得
 * @param 無し
 * @return string ブラウザタイプ
 */
function getBrowsType()
{
	$HTTP_USER_AGENT = getenv( "HTTP_USER_AGENT" );
	if( ereg( "Opera", $HTTP_USER_AGENT ) )
	{
		return "Opera";
	}
	elseif( ereg( "^Mozilla.4", $HTTP_USER_AGENT ) )
	{
		if( ereg( "MSIE", $HTTP_USER_AGENT )  )
		{ return "IE6互換"; }
		else
		{ return "NN4互換"; }
	}
	elseif( ereg( "^Mozilla.5", $HTTP_USER_AGENT ) )
	{ return "NN4互換"; }
	else
	{ return "Other"; }
}
/**
 * ディレクトリ完全削除(中にファイルがあっても削除する)
 * @param string ファイルパス
 * @return void 無し
 */
function rmDirs($dir) {
	if ($handle = opendir("$dir")) {
		while (false !== ($item = readdir($handle))) {
			if ($item != "." && $item != "..") {
				if (is_dir("$dir/$item")) {
					rmDirs("$dir/$item");
				} else {
					unlink("$dir/$item");
				}
			}
		}
		closedir($handle);
		rmdir($dir);
	}
}
/**
 * 指定ディレクトリ以下のファイルリストをすべて取得する
 * @param string ファイルパス
 * @param bool   自分自身をリストにいれるか
 * @return void 無し
 */
function getDirList($dirpath='' , $flag = true )
{
	if ( strcmp($dirpath,'')==0 ) die('dir name is undefind.'); 
	$file_list = array();
	$dir_list = array();
	if( ($dir = @opendir($dirpath) ) == FALSE ) {
		die( "dir {$dirpath} not found.");
	}

	while ( ($file=readdir( $dir )) !== FALSE ){
		if ( is_dir( "$dirpath/$file" ) ){ 
			if( strpos( $file ,'.' ) !== 0 ){      
				$dir_list["$file"] = getDirList( "$dirpath/$file" , $flag );  
			}
		} else {
			if( $flag ){
				array_push($file_list, $file);
			} else {
				if( strpos( $file , '.' )!==0 ) array_push( $file_list , $file);
			}
		}
	}
	return array( "file"=>$file_list , "dir"=>$dir_list);
}
/**
 * クライアントタイプの判別
 */
function isClientTerminal() {
	$ua = $_SERVER['HTTP_USER_AGENT'];
	if (
		preg_match("/DoCoMo/",$ua) ||
		preg_match("/J-PHONE/",$ua) ||
		preg_match("/Vodafone/",$ua) ||
		preg_match("/MOT/",$ua) ||
		preg_match("/SoftBank/",$ua) ||
		preg_match("/PDXGW/",$ua) ||
		preg_match("/UP.Browser/",$ua) ||
		preg_match("/ASTEL/",$ua) ||
		preg_match("/DDIPOCKET/",$ua) ) {
		return "FP"; // futurePhone ガラケー
	} elseif (
		preg_match("/ikkatsuPC/",$ua) ) {
		return "PC"; // iPad/Mac
	} elseif (
		preg_match("/iPhone/",$ua) ||
		preg_match("/iPod/",$ua) ||
		preg_match("/Android/",$ua)
	) {
		return "SP"; // smartPhone スマホ
	} elseif (
		preg_match("/iPad/",$ua) ) {
		return "IOS"; // iPad/Mac
	} elseif (
		preg_match("/Mac/",$ua) ) {
		return "Mac"; // iPad/Mac
	} elseif (
		preg_match("/CFNetwork/",$ua) ||
		preg_match("/Darwin/",$ua) ) {
		return "IOS_WV"; // iPad/MacのWebViewから
	} else {
		return "PC"; // PC
	}
}

/**
 * アプリ対応クライアントの判別
 */
function isAppliClient() {
	$ua = $_SERVER['HTTP_USER_AGENT'];
	if (
		preg_match("/DoCoMo/",$ua) ||
		preg_match("/J-PHONE/",$ua) ||
		preg_match("/Vodafone/",$ua) ||
		preg_match("/MOT/",$ua) ||
		preg_match("/SoftBank/",$ua) ||
		preg_match("/PDXGW/",$ua) ||
		preg_match("/UP.Browser/",$ua) ||
		preg_match("/ASTEL/",$ua) ||
		preg_match("/DDIPOCKET/",$ua) ||
		preg_match("/Mac/",$ua) ||
		preg_match("/Windows NT 5/",$ua)  )
	{
		return false; // アプリ非対応
	} else {
		return true; // アプリ対応
	}
}

function isAppTerminal() {
	$ua = $_SERVER['HTTP_USER_AGENT'];

	if (
		preg_match("/ikkatsuPC/",$ua) ) {
		return "PC"; // iPad/Mac
	} elseif (
		preg_match("/iPhone/",$ua) ||
		preg_match("/iPod/",$ua) ||
		preg_match("/iPad/",$ua) ) {
		return "IOS"; // iOS
	} elseif (
		 preg_match("/Android/",$ua ) ) {
		return "AND"; // Android
	} elseif (
		preg_match("/Mac/",$ua) ) {
		return "Mac"; // Mac
	} elseif (
		preg_match("/NT 5/",$ua) ) {
		return "XP"; // XP
	} elseif ( /* 20150219 Vistaの判定追加 */
		preg_match("/NT 6\.0/",$ua) ) {
		return "VIS"; // Vista
	} else {
		return "PC"; // PC
	}
}

/**
 * スマートフォンかどうか判別
 * @param 無し
 * @return bool TRUE=スマホ FALSE=スマホ以外
 */
function isSmartPhone() {
	$useragents = array(
	'iPhone', // Apple iPhone
	'iPod', // Apple iPod touch
	'iPad', // Apple iPad
	'Android', // 1.5+ Android
	'dream', // Pre 1.5 Android
	'CUPCAKE', // 1.5+ Android
	'blackberry9500', // Storm
	'blackberry9530', // Storm
	'blackberry9520', // Storm v2
	'blackberry9550', // Storm v2
	'blackberry9800', // Torch
	'webOS', // Palm Pre Experimental
	'incognito', // Other iPhone browser
	'webmate' // Other iPhone browser
	);
	$pattern = '/'.implode('|', $useragents).'/i';
	return preg_match($pattern, $_SERVER['HTTP_USER_AGENT']);
}
/**
 * iPhoneかどうか判別
 * @param 無し
 * @return bool TRUE=iPhone FALSE=iPhone以外
 */
function isIPhone() {
	$useragents = array(
	'iPhone', // Apple iPhone
	'iPod', // Apple iPod touch
	'iPad', // Apple iPad
	);
	$pattern = '/'.implode('|', $useragents).'/i';
	return preg_match($pattern, $_SERVER['HTTP_USER_AGENT']);
}
/**
 * Androidかどうか判別
 * @param 無し
 * @return bool TRUE=Android FALSE=Android以外
 */
function isAndroid() {
	$useragents = array(
	'Android', // 1.5+ Android
	'dream', // Pre 1.5 Android
	);
	$pattern = '/'.implode('|', $useragents).'/i';
	return preg_match($pattern, $_SERVER['HTTP_USER_AGENT']);
}

/**
 * クライアントタイプからテンプレート用フォルダを算出
 */
function isTemplateDir() {
	$client = isClientTerminal();
	switch($client) {
		case "PC":
		case "Mac":
			return "pc";
		default:
			return "sp";
	}
}

/**
 * カンマ等の区切りID列にマスタを与えて返還後リストを作成する
 * @param idリスト
 * @param マスタデータ
 * @param idリストのデリミタ
 * @param 出力用のデリミタ
 * @return string 文字列
 */
function ids2values($ids,$mst,$srcdlm=',',$dstdlm=',') {
	$tmp = explode($srcdlm,$ids);
	$arr = array();
	foreach( $tmp as $id ) {
		$arr[] = $mst[$id];
	}
	$ret = join($dstdlm,$arr);
	return $ret;
}

/**
 * 前方一致
 * $haystackが$needleから始まるか判定します。
 * @param string $haystack
 * @param string $needle
 * @return boolean
 */
function startsWith($haystack, $needle){
    return strpos($haystack, $needle, 0) === 0;
}
 
/**
 * 後方一致
 * $haystackが$needleで終わるか判定します。
 * @param string $haystack
 * @param string $needle
 * @return boolean
 */
function endsWith($haystack, $needle){
    $length = (strlen($haystack) - strlen($needle));
    // 文字列長が足りていない場合はFALSEを返します。
    if($length < 0) return FALSE;
    return strpos($haystack, $needle, $length) !== FALSE;
}
 
/**
 * 部分一致
 * $haystackの中に$needleが含まれているか判定します。
 * @param string $haystack
 * @param string $needle
 * @return boolean
 */
function matchesIn($haystack, $needle){
    return strpos($haystack, $needle) !== FALSE;
}

/**
 * ボットチェック
 * @param 無し
 * @return boolean
 */
function isAccessBot(){
	$bot_list = array (
	    'Googlebot',
	    'Yahoo! Slurp',
	    'Mediapartners-Google',
	    'msnbot',
	    'bingbot',
	    'MJ12bot',
	    'Ezooms',
	    'pirst; MSIE 8.0;',
	    'Google Web Preview',
	    'ia_archiver',
	    'Sogou web spider',
	    'Googlebot-Mobile',
	    'AhrefsBot',
	    'YandexBot',
	    'Purebot',
	    'Baiduspider',
	    'UnwindFetchor',
	    'TweetmemeBot',
	    'MetaURI',
	    'PaperLiBot',
	    'Showyoubot',
	    'JS-Kit',
	    'PostRank',
	    'Crowsnest',
	    'PycURL',
	    'bitlybot',
	    'Hatena',
	    'Hao',
	    'facebookexternalhit',
	    'bot'
	);

	$bot_name = 'unknown';
	foreach ($bot_list as $bot) {
		if (stripos($_SERVER['HTTP_USER_AGENT'], $bot) !== false) {
			$bot_name = $bot;
				return TRUE;
	    }
	}
	return FALSE;
}
/*
 * アクセスしているURLを取得
 * @param 無し
 * @return url文字列
*/
function getAccessURL(){
	return (empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
}

/*
 * 1x1の透明gifを返して処理を終了する
 * @param 無し
 * @return 無し
*/
function outputConversionImage() {
	header('Content-Type: image/gif');
	echo base64_decode("R0lGODlhAQABAIAAAAAAAAAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw%3D%3D");
	exit;
}
/*
 * サムネイルの作成（縮小して中央を切り出す）
 * @param strings ソースファイル
 * @param strings 出力ファイル
 * @param integer サムネイル横幅
 * @param integer サムネイル立幅
 * @return 無し
*/
function thumbnailImate($srcFile,$dstFile,$outW,$outH) {
	$size = getimagesize($srcFile);
	if( empty($size) ) return FALSE;
	$srcW = $size[0];
	$srcH = $size[1];
	$resW = 0;
	$resH = 0;
	// 縦長の場合
	if( $srcW < $srcH ) {
		$resW = $outW;
		$resH = (int)($outH*($srcH/$srcW));
	} else {
	// 横長の場合
		$resW = (int)($outW*($srcW/$srcH));
		$resH = $outH;
	}
	$command = "convert {$srcFile} -size {$srcW}x{$srcH} -resize {$resW}x{$resH} -gravity center -crop {$outW}x{$outH}+0+0 {$dstFile}";
	system($command);
	//dout($command);
	return TRUE;
}

/*
 * トップ画像の作成（横幅を合わせて中央を切り出す）
 * @param strings ソースファイル
 * @param strings 出力ファイル
 * @param integer サムネイル横幅
 * @param integer サムネイル立幅
 * @return 無し
*/
function topImate($srcFile,$dstFile,$outW,$outH) {
	$size = getimagesize($srcFile);
	if( empty($size) ) return FALSE;
	$srcW = $size[0];
	$srcH = $size[1];
	$resW = 0;
	$resH = 0;
	// 横を960にする
	$resW = 960;
	$resH = (int)(960*($srcH/$srcW));

	$command = "convert {$srcFile} -size {$srcW}x{$srcH} -resize {$resW}x{$resH} -gravity center -crop {$outW}x{$outH}+0+0 {$dstFile}";
	system($command);
	//dout($command);
	return TRUE;
}

/*
 * ディレクトリ階層以下のコピー
 * @param strings コピー元ディレクトリ
 * @param strings コピー先ディレクトリ
 * @return boolian 結果
*/
function dirCopy($dir_name, $new_dir)
{
  if (!is_dir($new_dir)) {
    mkdir($new_dir);
  }
 
  if (is_dir($dir_name)) {
    if ($dh = opendir($dir_name)) {
      while (($file = readdir($dh)) !== false) {
        if ($file == "." || $file == "..") {
          continue;
        }
        if (is_dir($dir_name . "/" . $file)) {
          dirCopy($dir_name . "/" . $file, $new_dir . "/" . $file);
        }
        else {
          copy($dir_name . "/" . $file, $new_dir . "/" . $file);
        }
      }
      closedir($dh);
    }
  }
  return true;
}

/*
 * パスの中から主ファイル名を取得
 * @param strings パス
 * @return boolian 主ファイル名
*/
function mainFileName($path) {
	$temp = pathinfo($path);
	return $temp['filename'];
}

/*
 * 配列から要素を削除する
 * @param array 元配列
 * @param array 削除要素
 * @return boolian 主ファイル名
*/
function array_remove($target,$remove) {
	//削除実行
	$result = array_diff($target, $remove);
	//indexを詰める
	$result = array_values($result);
	return $result;
}
function sanitize($a) { 
	$_a = array(); 
	if( is_array($a) == FALSE ) return htmlentities($a,ENT_QUOTES,"UTF-8");
	foreach($a as $key=>$value) { 
		if (is_array($value)) { 
			$_a[$key] = sanitize($value); 
			} else { 
			$_a[$key] = htmlentities($value,ENT_QUOTES,"UTF-8"); 
		} 
	}
	return $_a; 
}
/*
 * 全角スペースを半角にした上でのtrim
 * @param strings 文字列
 * @return strings 変換後文字列
*/
function strim($str) {
	$str = trim(str_replace("　"," ",$str));     // 全角スペースは半角に
	$str = preg_replace('/\s(?=\s)/', '', $str); // 複数スペースを1つへ変換
	return $str; 
}

?>