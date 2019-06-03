<?PHP
/**
 * メール送信クラス
 *
 * メール送信関連のクラス
 *
 * @access public
 * @author Atsushi Taguchi
 * @copyright Copyright (c) 2013, Tryit
 * @version 1.00
 * @since 2013/07/01
 */
/**
 */
//include_once("cMBStrExt.php");	// マルチバイト文字の拡張制御
class cMail
{
	/*
	var $mail_body;
	var $mail_subject;
	var $mail_to;
	var $mail_bcc;
	var $mail_from;
	*/
	/**
	 * コンストラクタ
	 */
	function __construct() {
		/*
		$this->mail_body = "";
		$this->mail_subject = "";
		$this->mail_to = "";
		$this->mail_bcc = "";
		$this->mail_from = ADMIN_FROM; // デフォルト送り元
		*/
	}

	/**
	 * メールを送信する
	 * @param string (To)送信先
	 * @param string 本文
	 * @param string サブジェクト
	 * @param string (From)送信元
	 * @param string  送信元名
	 * @return bool 送信結果
	 */
	function send($to,$body,$subject,$from_email,$from_name = null,$cc = null,$bcc = null)
	{
		// PHP5.2未満のサーバの場合、 ISO-2022-JP-MS を ISO-2022-JP にする
		//if( DEBUG_MODE == TRUE ) {
			fLog("▼ メール送信 ------------------------------------");
			fLog($to);
			fLog($subject);
			fLog("▲ メール送信 ------------------------------------");
		//}
/*
			dout("▼ メール送信 ------------------------------------");
			dout($to);
			dout($subject);
			dout("▲ メール送信 ------------------------------------");
*/
		// body文字列を一括エンティティデコード
		$body = html_entity_decode($body,ENT_QUOTES,"UTF-8"); 
		$subject = html_entity_decode($subject,ENT_QUOTES,"UTF-8"); 

		$headers  = "MIME-Version: 1.0 \n" ;
		if( !empty($from_name) ) {
			$headers .= "From: " .
			       "".mb_encode_mimeheader (mb_convert_encoding($from_name,"ISO-2022-JP-MS","UTF-8")) ."" .
			       "<".$from_email."> \n";
			$headers .= "Reply-To: " .
			       "".mb_encode_mimeheader (mb_convert_encoding($from_name,"ISO-2022-JP-MS","UTF-8")) ."" .
			       "<".$from_email."> \n";
		} else {
			$headers .= "From: " . $from_email . "\n";
			$headers .= "Reply-To: " . $from_email . "\n";
		}
		if( !empty($cc) ) {
			$headers .= "Cc: " . $cc . "\n";
		}
		if( !empty($bcc) ) {
			$headers .= "Bcc: " . $bcc . "\n";
		}
		// サーバによって、BODYが１行目から始まらない場合は、改行無し、それ以外は改行有りを使用する
		$headers .= "Content-Type: text/plain;charset=ISO-2022-JP";
		$body = str_replace("\r\n","\n",$body);
		$body = str_replace("\r","\n",$body);
		$body = mb_convert_encoding($body, "ISO-2022-JP-MS","UTF-8");
		/* Mail, optional paramiters. */
		$sendmail_params  = "-f $from_email";
//		$subject = mb_encode_mimeheader($subject,'ISO-2022-JP-MS', 'UTF-8');
		$subject = mb_encode_mimeheader($subject,'ISO-2022-JP-MS', 'B', "\n");
		//$subject = mb_encode_mimeheader(mb_convert_encoding($subject,"ISO-2022-JP","UTF-8"));
		if( DEBUG_MODE == TRUE ) {
			fLog("------------------------------------\n");
			fLog($to."\n");
			fLog($subject."\n");
			fLog($body."\n");
			fLog($headers."\n");
			fLog($sendmail_params."\n");
			fLog("------------------------------------\n");
		}
		$result = mail($to, $subject, $body, $headers, $sendmail_params);
		if( $result ) {
			fLog("result : OK.");
		} else {
			fLog("result : NG.");
		}
		       
		return $result;
	}
}
?>
