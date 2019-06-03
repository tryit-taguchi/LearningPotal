<?PHP
/**
 * HTML生成を支援するクラス（静的クラス）
 *
 * HTML生成を支援するクラス
 *
 * @access public
 * @author Atsushi Taguchi
 * @copyright Copyright (c) 2013, Tryit
 * @version 1.00
 * @since 2013/07/01
 */
/**
 */
class cHtml
{
	/**
	 * 単数数行テキスト
	 * @param string  文字列
	 * @param string 前に付与する文字列（＄等）
	 * @param string 後に付与する文字列（円等）
	 * @param string 数字が無かった場合に空文字にするかどうかのフラグ
	 * @param string 文字列が空だった場合の文字列
	 * @param mixed  未選択用の値
	 * @param array  値リスト
	 * @return void  無し
	 */
	static public function singleLineText($str,$bstr="",$astr="",$nflg=TRUE,$notext = "")
	{
		if( trim($str) == "" ) return $notext;
		if( $nflg == TRUE )
			$str = ($str=="")?"":$bstr . $str . $astr;
		else
			$str = $bstr . $str . $astr;
		return $str;
	}
	/**
	 * テキストを強制改行する
	 * @param string 文字列
	 * @param string 文字列が空だった場合の文字列
	 * @param integer 強制改行文字数
	 * @return void  無し
	 */
	static public function multiLineText($str,$notext = "",$maxcount = 0) {
		if( trim($str) == "" ) return $notext;
		if( $maxcount > 0 ) {
			if( mb_strlen($str) > $maxcount) {
				$str = mb_substr($str,0,$maxcount);
				$str .= "...";
			}
		}
		$str = str_replace("\n","<br />",$str);
		return $str;
	}
	/**
	 * 数値をフォーマット整形して単位を追記する
	 * @param string 数値
	 * @param string 前に付与する文字列（＄等）
	 * @param string 後に付与する文字列（円等）
	 * @param string 数字が無かった場合に空文字にするかどうかのフラグ
	 * @param string 数字が0だった場合の文字列
	 * @return string 変換後文字列
	 */
	static public function numberText($number,$bstr="",$astr="",$zflg=TRUE,$notext = "") {
		if( $notext != "" )
			if( (int)trim($number) == 0 ) return $notext;
		if( $zflg == TRUE )
			$str = ($number==0)?"":$bstr.number_format($number).$astr;
		else
			$str = $bstr.number_format($number).$astr;
		return $str;
	}
	/**
	 * URLリンク整形
	 * @param string  URL
	 * @param string  URLが指定されなかった場合の文字列
	 * @return string 変換後文字列
	 */
	static public function urlText($url,$notext = "") {
		if( trim($url) == "" ) return $notext;
		if( strpos($url,"http://") === FALSE )
			$url = "http://" . $url;
		return "<a href='".$url."' target='_blank'>".$url."</a>";
	}
	/**
	 * 郵便番号整形
	 * @param string 郵便番号文字列
	 * @param string 前に付与する文字列
	 * @param string 後に付与する文字列
	 * @param string 文字を付与するかどうかのフラグ
	 * @param string 郵便番号が無かった場合の文字列
	 * @return string 変換後文字列
	 */
	static public function zipText($str,$bstr="",$astr="",$nflg=TRUE,$notext = "") {
		if( trim($str) == "" ) return $notext;
		$tmp = explode("-",$str);
		$str = sprintf("%03d-%04d",$tmp[0],$tmp[1]);
		if( $nflg == TRUE )
			$str = ($str=="")?"":$bstr . $str . $astr;
		else
			$str = $bstr . $str . $astr;
		return $str;
	}
	/**
	 * 日付を年月日整形
	 * @param string datetime文字列
	 * @param string デリミタ
	 * @param string 付与する文字列
	 * @return string 変換後文字列
	 */
	static public function dateText($datestr,$drim_mode="/",$unit_type="") {
		$tmp = explode(" ",$datestr);
		$ymd = explode("-",$tmp[0]);
		if( $drim_mode == "年月日" ) {
			if( (int)$ymd[0]!=0 ) $str .= $ymd[0] . "年";
			if( (int)$ymd[1]!=0 ) $str .= $ymd[1] . "月";
			if( (int)$ymd[2]!=0 ) $str .= $ymd[2] . "日";
		} else {
			$tmp = array();
			if( (int)$ymd[0]!=0 ) $tmp[] = $ymd[0];
			if( (int)$ymd[1]!=0 ) $tmp[] = $ymd[1];
			if( (int)$ymd[2]!=0 ) $tmp[] = $ymd[2];
			$str = implode($drim_mode,$tmp);
		}
		if( (int)$ymd[0]!=0 ) $str = $unit_type . $str;
		return $str;
	}
	/**
	 * 日付を月日整形
	 * @param string datetime文字列
	 * @param string デリミタ
	 * @param string 付与する文字列
	 * @return string 変換後文字列
	 */
	static public function date2MDText($datestr,$drim_mode="/",$unit_type="")
	{
		if( empty($datestr) ) return "";
		if( $datestr == "0000-00-00" ) return "";
		if( $datestr == "0000-00-00 00:00:00" ) return "";
		$ymd = explode("-",$datestr);
		if( $drim_mode == "月日" ) {
			if( (int)$ymd[1]!=0 ) $str .= (int)$ymd[1] . "月";
			if( (int)$ymd[2]!=0 ) $str .= (int)$ymd[2] . "日";
		} else {
			$tmp = array();
			if( (int)$ymd[1]!=0 ) $tmp[] = (int)$ymd[1];
			if( (int)$ymd[2]!=0 ) $tmp[] = (int)$ymd[2];
			$str = implode($drim_mode,$tmp);
		}
		if( (int)$ymd[0]!=0 ) $str = $unit_type . $str;
		return $str;
	}

	/**
	 * 日付を月日時間整形
	 * @param string datetime文字列
	 * @param string デリミタ
	 * @return string 変換後文字列
	 */
	static public function datetimeMDText($datestr,$drim_mode="/") {
		if( empty($datestr) ) return "";
		if( $datestr == "0000-00-00" ) return "";
		if( $datestr == "0000-00-00 00:00:00" ) return "";
		$tmp = explode(" ",$datestr);
		$ymd = explode("-",$tmp[0]);
		$hms = explode(":",$tmp[1]);
		return (int)$ymd[1] . "/" . (int)$ymd[2] . " " . (int)$hms[0] . ":" . (int)$hms[1] . ":" . (int)$hms[2]  ;
	}

	/**
	 * HTMLをエスケープして文字列を表示する
	 * @param string HTML文字列
	 * @param bool   強制改行する場合は、TRUE
	 * @return string 変換後文字列
	 */
	static public function toText($str,$lf_flg = FALSE)
	{
		$str = htmlspecialchars($str,ENT_QUOTES);
		if( $lf_flg == TRUE ) {
			$str = self::br($str);
		}
		return $str;
	}
	/**
	 * 文字列の改行をbrタグにする
	 * @param string HTML文字列
	 * @return string 変換後文字列
	 */
	static public function br($str)
	{
		$str = str_replace("\n","<br>",$str);
		return $str;
	}

	/**
	 * ページネーションタグ
	 * @param object ページ情報(cDbExtPagination::getPageInfo() で取得の配列)
	 * @param array  リンクに追加するパラメータ配列
	 * @return void  無し
	 */
	static public function pagination($pages,$prams = null) {
		// リンクに貼り付けるパラメータ
		$httpQuery = "";
		if( !empty($prams) ) {
			$httpQuery = "&".http_build_query($prams);
		}
		// リンク先の作成
		$p = PAGE_PRAM_NAME;
		?>
<!-- ページネーション -->
<div class="pager01">
	<!-- ページ始まり -->
	<? if( $pages->firstEnable ) { ?>
		<a href="?<?= $p ?>=<?= $pages->firstNum ?><?= $httpQuery ?>" class="prev arrowLeft">最初</a>
	<? } else { ?>
		<a href="#" class="prev arrowLeft gray">最初</a>
	<? } ?>
	<? if( $pages->beforeEnable ) { ?>
		<a href="?<?= $p ?>=<?= $pages->beforeNum ?><?= $httpQuery ?>" class="prev arrowLeft">前へ</a>
	<? } else {?>
		<a href="#" class="prev arrowLeft gray">前へ</a>
	<? } ?>
	<? foreach((array)$pages->pageData as $no=>$data) {
		if( $data->currentFlg==1 ) { ?>
			<span class="current"><?= $data->pageNum ?></span>
		<? } else { ?>
			<a href="?<?= $p ?>=<?= $data->listNum ?><?= $httpQuery ?>"><?= $data->pageNum ?></a>
		<? } ?>
	<? } ?>
	<? if( $pages->afterEnable ) { ?>
		<a href="?<?= $p ?>=<?= $pages->afterNum ?><?= $httpQuery ?>" class="next arrowRight">次へ</a>
	<? } else { ?>
		<a href="#" class="next arrowRight gray">次へ</a>
	<? } ?>
	<? if( $pages->lastEnable ) { ?>
		<a href="?<?= $p ?>=<?= $pages->lastNum ?><?= $httpQuery ?>" class="next arrowRight">最後</a>
	<? } else { ?>
		<a href="#" class="next arrowRight gray">最後</a>
	<? } ?>
	<!-- ページ終わり -->
</div>
<!-- /ページネーション -->
	<?
		/* CSS例
		.pagination{
			float:right;
			overflow:hidden;
		}
		.pagination ul{
			overflow:hidden;
			list-style-type: none;
		}
		.pagination ul li{
			float:left;
			margin:0 0 0 5px;
			padding:3px 5px;
			border:solid 1px #0070C0;
			font-weight:bold;
		}
		.pagination ul li.now{
			background:#0070C0;
			color:#FFFFFF;
		}
		.pagination ul li a{
			color:#0070C0;
		}
		.pagination ul li:hover{
			background:#0070C0;
		}
		.pagination ul li:hover a{
			color:#FFFFFF;
		}
		.pagination ul li.sleep{
			color:#C0C0C0;
		}
		.pagination ul li.sleep:hover{
			color:#C0C0C0;
			background:#FFFFFF;
		}
		*/
	}

	/**
	 * エラー表示タグ
	 * @param string エラータイトル
	 * @param array  エラー配列
	 * @return void  無し
	 */
	static public function errorDisplay($errorTitle,$errorMsg) {
		if( !empty($errorMsg) ) { ?>
		<div class="alert alert-block alert-error">
			<h4 class="alert-heading"><?= $errorTitle ?></h4>
			<ul>
			<? foreach($errorMsg as $etype=>$evalues) {
				if( is_array($evalues) ) {
					foreach($evalues as $erow=>$evalue) { ?>
							<li><?= $evalue ?></li>
					<? }
				}
			} ?>
			</ul>
		</div><?
		}
	}

	/**
	 * エラーがあるかどうか
	 * @param array  エラー配列
	 * @return bool  TRUE=ある FALSE=ない
	 */
	static public function isError($errorMsg) {
		if( !empty($errorMsg) ) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * 指定のエラーを出力
	 * @param array  エラー配列
	 * @param code   エラーコード
	 * @return mixed string=エラーメッセージ FALSE=エラー無し
	 */
	static public function assignError($errorMsg,$errorCode,$delimiter = "") {
		if( empty($errorMsg[$errorCode]) ) {
			return FALSE;
		} else {
			return join($delimiter,$errorMsg[$errorCode]);
		}
	}

	/**
	 * empty関数で掛かるもの以外
	 * @param string 文字列1
	 * @param string 文字列1があった場合に後ろに付与する文字列
	 * @return string  empty関数で掛かるもの以外の文字列
	 */
	static public function notEmpty($str,$str2="") {
		if( empty($str) ) {
			return "";
		} else {
			return $str . $str2;
		}
	}

	/**
	 * 金額の表示
	 * @param integer 金額
	 * @return string 整形された文字列
	 */
	static public function price($num,$unit='円') {
		$ret = "";
		if( $unit=='円' ) {
			$ret = !empty($num)?sprintf("%s円",number_format($num)):'';
		}
		if( $unit=="\\" ) {
			$ret = !empty($num)?sprintf("&yen;%s",number_format($num)):'';
		}
		return $ret;
	}

	/**
	 * レートの表示
	 * @param number 金額
	 * @return string 整形された文字列
	 */
	static public function rate($num,$unit='％') {
		$ret = "";
		$ret = !empty($num)?sprintf("%.3f%s",$num,$unit):'';
		return $ret;
	}

	/**
	 * 年の表示
	 * @param number 年
	 * @return string 整形された文字列
	 */
	static public function year($num,$unit='年') {
		$ret = "";
		$ret = !empty($num)?sprintf("%d%s",$num,$unit):'';
		return $ret;
	}

	/**
	 * エラー表示
	 * @param array エラー配列
	 * @return void  無し
	 */
	function displayError($errorMsg,$errorCode) {
		if( empty($errorMsg) ) return;
		$msg = self::assignError($errorMsg,$errorCode,"<br />");
		if( $msg !== FALSE ) {
			?><p class="error" style="color:red"><?= $msg ?></p><?
		}
	}

	/**
	 * OGP設定
	 * @param array エラー配列
	 * @return void  無し
	 */
	function setOGP($image,$description) {
		global $og;
		$og = array();
		if( !file_exists(DOCUMENT_ROOT . $image) ) {
			$og['image'] = "";
		} else {
			$og['image'] = "https://".SELF_HOST.$image;
		}
		$og['description'] = $description;
		return $og;
	}
}
?>
