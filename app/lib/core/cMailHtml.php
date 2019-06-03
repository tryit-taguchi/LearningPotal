<?PHP
/**
 * HTMLメール送信クラス
 *
 * HTMLメール送信関連のクラス
 *
 * @access public
 * @author Atsushi Taguchi
 * @copyright Copyright (c) 2013, Tryit
 * @version 1.00
 * @since 2013/07/01
 */
/**
 */
class cMailHtml
{
	/**
	 * コンストラクタ
	 */
	function __construct() {
	}

	/**
	 * メールを送信する
	 * @param string (To)送信先
	 * @param string 本文（HTML）
	 * @param string 本文（プレーンテキスト）
	 * @param string サブジェクト
	 * @param string (From)送信元
	 * @param string  送信元名
	 * @return bool 送信結果
	 */
	function send($to,$bodyHtml,$bodyText,$subject,$from_email,$from_name = null,$cc = null,$bcc = null)
	{
		// PHP5.2未満のサーバの場合、 ISO-2022-JP-MS を ISO-2022-JP にする
		if( DEBUG_MODE == TRUE ) {
			fLog("▼ HTMLメール送信 ------------------------------------");
			fLog($to);
			fLog($subject);
			fLog("▲ HTMLメール送信 ------------------------------------");
		}

		$headers = '';
		$body = '';
		// bodyHtml文字列を一括エンティティデコード
		$bodyHtml = html_entity_decode($bodyHtml,ENT_QUOTES,"UTF-8"); 
		$bodyText = html_entity_decode($bodyText,ENT_QUOTES,"UTF-8"); 
		$subject = html_entity_decode($subject,ENT_QUOTES,"UTF-8"); 
		$boundary = "--".uniqid(mt_rand());

		/* ヘッダー */
		$headers .= "Content-Type: multipart/alternative; boundary=\"{$boundary}\"\n";
		$headers .= "MIME-Version: 1.0 \n" ;
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
		
		/* ボディ */
		// プレインテキスト
		$body .= "--{$boundary}\n";
		$body .= "Content-Type: text/plain;charset=ISO-2022-JP\n";
		$body .= "Content-Transfer-Encoding: 7bit\n";
		$body .= "\n";
		//$bodyText = '';
		$body .= mb_convert_encoding($bodyText, "ISO-2022-JP-MS","UTF-8");
		$body .= "\n";
		$body .= "--{$boundary}\n";
		// サーバによって、BODYが１行目から始まらない場合は、改行無し、それ以外は改行有りを使用する
		$body .= "Content-Type: text/html;charset=ISO-2022-JP\n";
		$body .= "Content-Transfer-Encoding: 7bit\n";
		$body .= "\n";
		$body .= mb_convert_encoding($bodyHtml, "ISO-2022-JP-MS","UTF-8");
		$body .= "\n";
		$body .= "--{$boundary}--\n";
		/* Mail, optional paramiters. */
		$sendmail_params  = "-f $from_email";
		$subject = mb_encode_mimeheader($subject,'ISO-2022-JP-MS', 'UTF-8');
		if( DEBUG_MODE == TRUE ) {
			/*
			fLog("------------------------------------\n");
			fLog($to."\n");
			fLog($subject."\n");
			fLog($body."\n");
			fLog($headers."\n");
			fLog($sendmail_params."\n");
			fLog("------------------------------------\n");
			*/
		}
		$result = mail($to, $subject, $body, $headers, $sendmail_params);
		       
		return $result;
	}
}
?>
