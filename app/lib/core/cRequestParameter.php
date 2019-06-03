<?php
/**
 * リクエストパラメータクラス
 *
 * HTTPに引き渡されるリクエストパラメータを管理するクラス
 *
 * <pre>
 * lib/core/iProcessStart.php でコールされています。
 * ページネーション用パラメータ名は、
 * lib/core/iDefines.php にて設定されています。（デフォルト p ）
 * </pre>
 *
 * @access public
 * @author Atsushi Taguchi
 * @copyright Copyright (c) 2016, Tryit
 * @version 1.00
 * @since 2016/09/29
 */
/**
 */
class cRequestParameter
{
	var $get;       // GET渡し（連装配列）
	var $post;      // POST渡し（連装配列）
	var $session;   // セッション（連装配列）
	var $cookie;    // クッキー（連装配列）
	var $val;       // GET/POST/セッション/クッキー混合(優先順位 GET>POST>セッション>クッキー) ※セッション情報は読み出しのみ
	var $servar;    // スーパーグローバルの$_SERVER変数と同等
	var $form;      // フォーム保存用セッション
	/**
	 * コンストラクタ
	 */
	function __construct() {
		$this->get     = $this->sanitize($_GET);
		$this->post    = $this->sanitize($_POST);
		$this->cookie  = $this->sanitize($_COOKIE);
		$this->session = &$_SESSION[getcwd()];
		// GET優先型リクエストパラメータ生成
		if( empty($this->val) ) 
			$this->val   = array();
		//$this->val   = $this->cookie;
		if( !empty($this->session) ) 
			$this->val   = array_merge($this->val,$this->session);
		if( !empty($this->post) ) 
			$this->val   = array_merge($this->val,$this->post);
		if( !empty($this->get) )
			$this->val   = array_merge($this->val,$this->get);
		$this->servar  = &$_SERVER;
	}
	/**
	 * 一括サニタイジング
	 * @param mixed 変換元文字列
	 * @return mixed 変換後文字列
	 */
	private function sanitize($a) { 
		$_a = array(); 
		if( is_array($a) == FALSE ) return htmlentities($a,ENT_QUOTES,"UTF-8");
		foreach($a as $key=>$value) { 
			if (is_array($value)) { 
				$_a[$key] = $this->sanitize($value); 
			} else { 
				$_a[$key] = htmlentities($value,ENT_QUOTES,"UTF-8"); 
			} 
		}
		return $_a; 
	}
	/**
	 * サニタイジングしたデータを一括デコード
	 * @param mixed 変換元文字列
	 * @return mixed 変換後文字列
	 */
	private function sanitize_decode($a) { 
		$_a = array(); 
		if( is_array($a) == FALSE ) return html_entity_decode($a,ENT_QUOTES,"UTF-8");
		foreach($a as $key=>$value) { 
			if (is_array($value)) { 
				$_a[$key] = $this->sanitize_decode($value); 
			} else { 
				$_a[$key] = html_entity_decode($value,ENT_QUOTES,"UTF-8"); 
			}
		}
		return $_a; 
	}
	/**
	 * ページネーションデータの取得
	 * @param 無し
	 * @return integer ページ番号
	 */
	public function getPageNum() {
		$prgName = $_SERVER['PHP_SELF'];
		$p = PAGE_PRAM_NAME;
		if( isset($this->get[$p]) ) {
			$offSet = $this->session[$prgName][$p] = (int)$this->get[$p];
		} else {
			$offSet = $this->session[$prgName][$p];
		}
		return $offSet; 
	}

	/**
	 * ページネーションデータのリセット（検索条件が変更された時など）
	 * @param 無し
	 * @return void 無し
	 */
	public function resetPageNum() {
		$prgName = $_SERVER['PHP_SELF'];
		$p = PAGE_PRAM_NAME;
		$this->session[$prgName][$p] = 0;
		return; 
	}

	/**
	 * セッションをクリアする
	 * @param 無し
	 * @return void 無し
	 */
	public function clearSession() {
		$this->session = array();
		$_SESSION = array();
	}
}
?>