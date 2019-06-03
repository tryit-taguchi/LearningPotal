<?php
/**
 * バリデート（静的クラス）
 *
 * Webフォーム等から引き渡された値をバリデートする
 *
 * <pre>
 * *********************************************************
 * 　使い方
 * *********************************************************
 * ■基本例
 * if(!cValidate::required($name)){
 *    $err_msg['NAME'][] = "名前が".cValidate::$errorMessage;
 * }
 * ※その他のチェックについては、各個関数を参照の事
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
//機種依存文字判別用正規表現パターン
define('MDC_REGEX_ASCII'		,'[\x00-\x7F]');
define('MDC_REGEX_SJIS_TWOBYTES','[\x81-\x9F\xE0-\xFC][\x40-\x7E\x80-\xFC]');
define('MDC_REGEX_SJIS_PATTERN'	,'[\xA0-\xDF]|[\x87\xED\xEE\xFA-\xFC][\x40-\x7E\x80-\xFC]');
define('MDC_REGEX_PATTERN'		,'/^(?:'. MDC_REGEX_ASCII .'|'. MDC_REGEX_SJIS_TWOBYTES .')*?((?:'. MDC_REGEX_SJIS_PATTERN .')+)/');
class cValidate
{
	static $errorMessage;
	static $zipParam;
	/**
	 * コンストラクタ
	 */
	function cValidate() {
	}

	/**
	 * 未入力確認
	 * @param mixed  確認文字列または配列
	 * @return bool 成否
	 */
	static public function required($pram)
	{
		if( !is_array($pram) ) {
			if($pram == "") {
				self::$errorMessage = "必須入力です。"; return FALSE;
			}
		} else {
			if(empty($pram)) {
				self::$errorMessage = "選択されていません。"; return FALSE;
			}
		}
		return TRUE;
	}
	/**
	 * 文字長チェック
	 * @param string  確認文字列
	 * @param integer 制限文字数
	 * @return bool 成否
	 */
	static public function length($str,$lenMax)
	{
		if(mb_strlen($str) > $lenMax) {
			self::$errorMessage = $lenMax."文字以内でおねがいします。"; return FALSE;
		}
		return TRUE;
	}
	/**
	 * 文字長チェック
	 * @param string  確認文字列
	 * @param integer 最小制限文字数
	 * @param integer 最大制限文字数
	 * @return bool 成否
	 */
	static public function lengthRange($str,$lenMin,$lenMax)
	{
		if(mb_strlen($str) < $lenMin) {
			self::$errorMessage = $lenMin."文字以上でおねがいします。"; return FALSE;
		}
		if(mb_strlen($str) > $lenMax) {
			self::$errorMessage = $lenMax."文字以内でおねがいします。"; return FALSE;
		}
		return TRUE;
	}
	/**
	 * 整数値チェック
	 * @param string  確認文字列
	 * @param integer 最小制限文字数
	 * @param integer 最大制限文字数
	 * @return bool 成否
	 */
	static public function numberRange($str,$numMin,$numMax)
	{
		if($str === NULL) return;
		if($str > $numMax) {
			self::$errorMessage = $numMin . " から " . $lenMax."でおねがいします。"; return FALSE;
		}
		if($str < $numMin) {
			self::$errorMessage = $numMin . " から " . $lenMax."でおねがいします。"; return FALSE;
		}
		return TRUE;
	}
	/**
	 * 半角チェック
	 * @param string 確認文字列
	 * @return bool 成否
	 */
	static public function han($str)
	{
		if($str == "") return;
		if(ereg("[^[:alnum:] /@,:;!#%&'~\?\^\$\"\+\*\.\_\-]",$str)) {
			self::$errorMessage = "使用できない文字が入力されています。"; return FALSE;
		}
		return TRUE;
	}
	/**
	 * 半角英数チェック
	 * @param string 確認文字列
	 * @return bool 成否
	 */
	static public function hanEiSuu($str)
	{
		if($str == "") return;
		//if(ereg("[^[:alnum:]]",$str)) {
		if (!preg_match("/^[a-zA-Z0-9]+$/", $str)) {
			self::$errorMessage = "使用できない文字が入力されています。"; return FALSE;
		}
		return TRUE;
	}
	/**
	 * 半角英数（-_+含む）チェック
	 * @param string 確認文字列
	 * @return bool 成否
	 */
	static public function hanBar($str)
	{
		if($str == "") return;
		//if(ereg("[^[:alnum:]\+_-]",$str)) {
		if (!preg_match("/^[a-zA-Z0-9\+_-]+$/", $str)) {
			self::$errorMessage = "使用できない文字が入力されています。"; return FALSE;
		}
		return TRUE;
	}
	/**
	 * 半角数字（-含む）チェック
	 * @param string 確認文字列
	 * @return bool 成否
	 */
	static public function hanNum($str)
	{
		if($str == "") return;
		$chk = (int)$str;
		if ( strlen($chk) != strlen($str) ) {
			self::$errorMessage = "使用できない文字が入力されています。"; return FALSE;
		}
		return TRUE;
	}
	/**
	 * 半角英小文字の存在チェック
	 * @param string 確認文字列
	 * @return bool 成否
	 */
	static public function lower($str)
	{
		if($str == "") return;
		//if(!ereg("[[:lower:]]",$str)) {
		if (!preg_match("/^[a-z]\-+$/", $str)) {
			self::$errorMessage = "小文字以外の文字が入力されています。"; return FALSE;
		}
		return TRUE;
	}
	/**
	 * 半角英大文字の非存在チェック
	 * @param string 確認文字列
	 * @return bool 成否
	 */
	static public function upper($str)
	{
		if($str == "") return;
		//if(ereg("[[:upper:]]",$str)) {
		if (!preg_match("/^[A-Z]\-+$/", $str)) {
			self::$errorMessage = "大文字以外の文字が入力されています。"; return FALSE;
		}
		return TRUE;
	}
	/**
	 * ふりがなチェック
	 * @param string 確認文字列
	 * @return bool 成否
	 */
	static public function furigana($str)
	{
		if($str == "") return;
		if (preg_match("/^[ぁ-ん 　ー－]+$/u", $str))
			return TRUE;
		self::$errorMessage = "ふりがな以外の文字が入力されています。"; return FALSE;
	}
	/**
	 * メールアドレスチェック
	 * @param string 確認文字列
	 * @return bool 成否
	 */
	static public function eMail($str){
		//携帯アドレス対応版
		$list = explode(",",$str);
		foreach($list as $email) {
			$email = trim($email);
			if (preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $email)) {
			
			} else {
				self::$errorMessage = "正しく入力されていません。"; return FALSE;
			}
			if( strpos($email,',') !== FALSE ) {
				self::$errorMessage = "正しく入力されていません。"; return FALSE;
			}
			if( strpos($email,'..') !== FALSE ) {
				self::$errorMessage = "ピリオド「.」が続いているアドレス以外を入力して下さい。"; return FALSE;
			}
		}
		return TRUE;
		/*
		//携帯アドレス対応版
		$ok = ereg("^[0-9,a-z,A-Z,_,\.,-]+@[0-9,A-Z,a-z][0-9,a-z,A-Z,_,\.,-]+\.(aero|arpa|xyz|asia|biz|cat|com|coop|edu|gov|info|int|jobs|mil|mobi|museum|name|net|org|pro|tel|travel|ac|ad|ae|af|ag|ai|al|am|an|ao|aq|ar|as|at|au|aw|ax|az|ba|bb|bd|be|bf|bg|bh|bi|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|cr|cs|cu|cv|cx|cy|cz|dd|de|dj|dk|dm|do|dz|ec|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gg|gh|gi|gl|gm|gn|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|im|in|io|iq|ir|is|it|je|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|me|mg|mh|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|mv|mw|mx|my|mz|na|nc|ne|nf|ng|ni|nl|no|np|nr|nu|nz|om|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|ps|pt|pw|py|qa|re|ro|rs|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tl|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zr|zw)$",$str);
		if( $ok == false ) {
			self::$errorMessage = "正しく入力されていません。"; return FALSE;
		}
		if( strpos($str,',') !== FALSE ) {
			self::$errorMessage = "正しく入力されていません。"; return FALSE;
		}
		if( strpos($str,'..') !== FALSE ) {
			self::$errorMessage = "ピリオド「.」が続いているアドレス以外を入力して下さい。"; return FALSE;
		}
		return TRUE;
		*/
	}

	/**
	 * 携帯番号
	 * @param string 確認文字列
	 * @return bool 成否
	 */
	static public function telMobile($str){
		$tmp = explode('-',$str);
		$shigai = $tmp[0];
		$shinai = $tmp[1];
		$kanyuu = $tmp[2];
		if( $shigai == '070'
		 || $shigai == '080'
		 || $shigai == '090' ) {
			return self::tel2($str);
		} else {
			self::$errorMessage = "携帯電話の番号を入力してください。"; return FALSE;
		}
	}

	/**
	 * 電話・ファックス番号チェック
	 * @param string 確認文字列
	 * @return bool 成否
	 */
	static public function tel($str){
		$chktel = cUtility::getNumberStr($str); // 数字のみを取り出す

		if( substr($chktel,0,1) != '0' ) {
			if( strlen($chktel) > 16 ) {
				// 市外局番の頭が0でない場合国際番号なので、桁数のチェックのみ
				self::$errorMessage = "適切な内容を入力してください。"; return FALSE;
			}
		} else {
			// 国内番号
			//fLog_var($str);
			if( preg_match('/^[0-9]{2,5}-?[0-9]{2,5}-?[0-9]{4,5}$/', $str)) {
				$tmp = explode('-',$str);
				$shigai = $tmp[0];
				$shinai = $tmp[1];
				$kanyuu = $tmp[2];

				$chktel = $tmp[1].$tmp[2];
				if( !empty($chktel) ) {
					if( strlen($chktel) == substr_count($chktel, '0')
					 || strlen($chktel) == substr_count($chktel, '1')
					 || strlen($chktel) == substr_count($chktel, '2')
					 || strlen($chktel) == substr_count($chktel, '3')
					 || strlen($chktel) == substr_count($chktel, '4')
					 || strlen($chktel) == substr_count($chktel, '5')
					 || strlen($chktel) == substr_count($chktel, '6')
					 || strlen($chktel) == substr_count($chktel, '7')
					 || strlen($chktel) == substr_count($chktel, '8')
					 || strlen($chktel) == substr_count($chktel, '9') ) {
						// 同じ番号続き
						//fLog("同じ番号続き:".$chktel);
						self::$errorMessage = "適切な内容を入力してください。"; return FALSE;
					}
				}

				if( $shigai[0] != '0' ) {
					// 市外局番の頭が0でない場合
					self::$errorMessage = "適切な内容を入力してください。"; return FALSE;
				}
				if( $shigai == '020'
				 || $shigai == '050'
				 || $shigai == '070'
				 || $shigai == '080'
				 || $shigai == '090' ) {
					if( strlen($shigai.$shinai.$kanyuu) != 11 ) {
						// 携帯系の場合で11桁でない場合
						self::$errorMessage = "適切な内容を入力してください。"; return FALSE;
						if( in_array($shinai,array('0000','9999') ) ) {
							self::$errorMessage = "適切な内容を入力してください。"; return FALSE;
						}
					}
				} elseif( $shigai == '0120' ) {
					if( strlen($shigai.$shinai.$kanyuu) != 10 ) {
						// フリーダイヤルの場合で10桁でない場合（会社番号等が考えられるため考慮）
						self::$errorMessage = "適切な内容を入力してください。"; return FALSE;
						if( in_array($shinai,array('000') ) ) {
							self::$errorMessage = "適切な内容を入力してください。"; return FALSE;
						}
					}
				} else {
					if( strlen($shigai.$shinai.$kanyuu) != 10 ) {
						// 固定系の場合で10桁でない場合
						self::$errorMessage = "適切な内容を入力してください。"; return FALSE;
					}
					if( strlen($shigai) == 2 ) {
						if( strlen($shinai) != 4 ) {
							self::$errorMessage = "適切な内容を入力してください。"; return FALSE;
						}
						if( in_array($shinai,array('0000','1111','2222','4444','7777','8888','9999') ) ) {
							self::$errorMessage = "適切な内容を入力してください。"; return FALSE;
						}
					}
					if( strlen($shigai) == 3 ) {
						if( strlen($shinai) != 3 ) {
							self::$errorMessage = "適切な内容を入力してください。"; return FALSE;
						}
						if( in_array($shinai,array('000','111','999') ) ) {
							self::$errorMessage = "適切な内容を入力してください。"; return FALSE;
						}
					}
					if( strlen($shigai) == 4 ) {
						if( strlen($shinai) != 2 ) {
							self::$errorMessage = "適切な内容を入力してください。"; return FALSE;
						}
					}
				}
				
				return TRUE;
			}
			self::$errorMessage = "正しく入力されていません。"; return FALSE;
		}
	}

	/**
	 * 郵便番号チェック
	 * @param string 確認文字列
	 * @return bool 成否
	 */
	static public function zip($str){
		if( $str == '999-999' ) return TRUE; // 特殊番号

		if( $str == "-" || $str == "" ) {
			self::$errorMessage = "必須入力です。"; return FALSE;
		}
		if( preg_match("/^[0-9]{3}-?[0-9]{4}$/", $str)) {
			$obj = cUtility::getZip2Address($str);
			if( empty($obj->zipcode) ) {
				self::$errorMessage = "存在する番号を入力してください。"; return FALSE;
			} else {
				$tmp = explode("-",$str);
				if( (int)$tmp[1] == 0 ) {
					self::$errorMessage = "適切な内容を入力してください。"; return FALSE;
				} else {
					self::$zipParam = $obj;
				}
			}
			return TRUE;
		}
		self::$errorMessage = "正しく入力されていません。"; return FALSE;
	}

	/**
	 * パスワードチェック
	 * @param string 確認文字列(半角アルファベット大文字小文字数字記号(# $ - = ? @ _))
	 * @return bool 成否
	 */
	static public function password($str){
/*
		$errorCnt = 0;
		$dst = preg_replace('/[A-Z]+/',"", $str);
		if( $str == $dst ) {
			$errorCnt++;
			//self::$errorMessage = "半角大文字を含めて下さい。"; return FALSE;
		}
		$dst = preg_replace('/[a-z]+/',"", $str);
		if( $str == $dst ) {
			$errorCnt++;
			//self::$errorMessage = "半角小文字を含めて下さい。"; return FALSE;
		}
		$dst = preg_replace('/[0-9]+/',"", $str);
		if( $str == $dst ) {
			$errorCnt++;
			//self::$errorMessage = "半角数字を含めて下さい。"; return FALSE;
		}
		$dst = preg_replace('/[\#\$\-\=\?\@\_]+/','', $str);
		if( $str == $dst ) {
			$errorCnt++;
			//self::$errorMessage = "# $ - = ? @ _ いずれかの記号を含めて下さい。"; return FALSE;
		}
*/
		$errorCnt = 0;
		$dst = preg_match('/[A-Z]+/', $str);
		if( $dst == 1 ) {
			$errorCnt++;
			//self::$errorMessage = "半角大文字を含めて下さい。"; return FALSE;
		}
		$dst = preg_match('/[a-z]+/', $str);
		if( $dst == 1 ) {
			$errorCnt++;
			//self::$errorMessage = "半角小文字を含めて下さい。"; return FALSE;
		}
		$dst = preg_match('/[0-9]+/', $str);
		if( $dst == 1 ) {
			$errorCnt++;
			//self::$errorMessage = "半角数字を含めて下さい。"; return FALSE;
		}
		$dst = preg_match('/[\#\$\-\=\?\@\_]+/', $str);
		if( $dst == 1 ) {
			$errorCnt++;
			//self::$errorMessage = "# $ - = ? @ _ いずれかの記号を含めて下さい。"; return FALSE;
		}
		if( $errorCnt < 3 ) {
			self::$errorMessage = "パスワードは半角大文字・小文字・数字・記号のいずれか３種類を含めて下さい。"; return FALSE;
		}
		$dst = preg_replace('/[A-Z]+/',"", $str);
		$dst = preg_replace('/[a-z]+/',"", $dst);
		$dst = preg_replace('/[0-9]+/',"", $dst);
		$dst = preg_replace('/[\#\$\-\=\?\@\_]+/','', $dst);
		if( !empty($dst) ) {
			self::$errorMessage = "使用できない文字が含まれています。"; return FALSE;
		}

		return TRUE;
	}
	/**
	 * 日付チェック
	 * @param integer 年
	 * @param integer 月
	 * @param integer 日
	 * @return bool 成否
	 */
	static public function date($y,$m,$d){
		if( (int)$m == 0 && (int)$d==0 && (int)$y==0 )
			return TRUE;
		$tmp1 = sprintf("%04d-%02d-%02d",(int)$y,(int)$m,(int)$d);
		$tmp2 = date("Y-m-d",mktime(0,0,0,(int)$m,(int)$d,(int)$y));
		if( $tmp1 == $tmp2 )
			return TRUE;
		self::$errorMessage = "正しい日付ではありません。"; return FALSE;
	}
	/**
	 * 携帯メールアドレスチェック
	 * @param string メールアドレス
	 * @return bool 成否 true=携帯 false=非携帯
	 */
	static public function eMailmobile($str){
		$mobiles = array(
				"docomo.ne.jp","mopera.net","dwmail.jp",
				"softbank.ne.jp","i.softbank.jp",
				"disney.ne.jp",
				"d.vodafone.ne.jp","h.vodafone.ne.jp","t.vodafone.ne.jp","c.vodafone.ne.jp","r.vodafone.ne.jp","k.vodafone.ne.jp","n.vodafone.ne.jp","s.vodafone.ne.jp","q.vodafone.ne.jp",
				"jp-d.ne.jp","jp-h.ne.jp","jp-t.ne.jp","jp-c.ne.jp","jp-r.ne.jp","jp-k.ne.jp","jp-n.ne.jp","jp-s.ne.jp","jp-q.ne.jp",
				"ezweb.ne.jp",
				"ido.ne.jp",
				"sky.tkk.ne.jp","sky.tkc.ne.jp",
				"sky.tu-ka.ne.jp",
				"pdx.ne.jp",
				"di.pdx.ne.jp","dj.pdx.ne.jp",
				"dk.pdx.ne.jp","wm.pdx.ne.jp",
				"willcom.com","wcm.ne.jp",
				"emnet.ne.jp","emobile.ne.jp",
			);
		for($i=0;$i<count($mobiles);$i++) {
			if( strpos( $str,$mobiles[$i]) > 0 ) {
				return TRUE;
			}
		}
		self::$errorMessage = "携帯以外のメールアドレスが入力されています。"; return FALSE;
	}
	/**
	 * 半角カナ・機種依存文字の有無
	 * @param string 確認文字列
	 * @return bool 成否
	 */
	static public function modelDependenceCharacter($arg) {
		if($arg == "") return TRUE;
		preg_match(MDC_REGEX_PATTERN,$arg,$match);
		if($match[0] != "") {
			self::$errorMessage = "半角カナ・機種依存文字が含まれています。"; return FALSE;
		}
		return TRUE;
	}
	/**
	 * 数字確認
	 * @param mixed  確認文字列または配列
	 * @return bool 成否
	 */
	static public function number($pram)
	{
		if($pram == "") {
			self::$errorMessage = "必須入力です。"; return FALSE;
		}
		if( !self::isPositiveNumber($pram) ) {
			self::$errorMessage = "数値以外の入力がされています。"; return FALSE;
		}
		return TRUE;
	}
	/**
	 * 整数・自然数チェック
	 * @param string 確認文字列（数字）
	 * @return bool 成否
	 */
	static public function isPositiveNumber($num) {
		if( trim($num) === "") return TRUE;
		if( is_numeric($num) == TRUE ) {
			if( (int)$num < 0 ) return FALSE;
			else                return TRUE;
		} else {
			return FALSE;
		}
		if( trim($num) == "") return FALSE;
	}

	/**
	 * 全角とカタカナのみ
	 * @param string 確認文字列
	 * @return bool 成否
	 */
	static public function katakanaFull($str){
		//fLog($str);
		if(!preg_match("/^[ァ-ヶー　]+$/u",$str)){
			//fLog("引っかかった");
			self::$errorMessage = "カタカナ・長音記号（ハイフン除く）・スペースのみで入力してください。"; return FALSE;
		}
		return TRUE;
	}

	/**
	 * 住所用カナ
	 * @param string 確認文字列
	 * @return bool 成否
	 */
	static public function katakanaAddress($str){
		if(!preg_match("/^[０-９ァ-ヶー　]+$/u",$str)){
			self::$errorMessage = "カタカナ・長音記号（ハイフン除く）・数字のみで入力してください。"; return FALSE;
		}
		return TRUE;
	}

	/**
	 * 住所用カナ
	 * @param string 確認文字列
	 * @return bool 成否
	 */
	static public function hirakanaAddress($str){
		if(!preg_match("/^[０-９ぁ-ゖー　]+$/u",$str)){
			self::$errorMessage = "ひらがな・長音記号（ハイフン除く）・数字のみで入力してください。"; return FALSE;
		}
		return TRUE;
	}

	/**
	 * ローマ字
	 * @param string 確認文字列
	 * @return bool 成否
	 */
	static public function alphabet($str){
		if(!preg_match("/^[A-Za-z]+$/",$str)){
			self::$errorMessage = "アルファベットのみで入力してください。"; return FALSE;
		}
		return TRUE;
	}

	/**
	 * 半角英数チェック
	 * @param string 確認文字列
	 * @return bool 成否
	 */
	static public function alphanumber($str){
		if(!preg_match("/^[a-zA-Z0-9]+$/u",$str)){
			self::$errorMessage = "半角英数時のみで入力してください。"; return FALSE;
		}
		return TRUE;
	}
}
?>