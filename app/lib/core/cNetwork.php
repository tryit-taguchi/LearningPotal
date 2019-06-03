<?PHP
/**
 * ネットワーク関連クラス（静的クラス）
 *
 * ネットワーク関連の機能を提供します
 *
 * @access public
 * @author Atsushi Taguchi
 * @copyright Copyright (c) 2013, Tryit
 * @version 1.00
 * @since 2013/07/01
 */
/**
 */
class cNetwork
{
	/**
	 * 指定のIPが指定のCIDR範囲に収まっているかチェックする
	 * @param string 比較IP
	 * @param mixed 比較対象のIP(カンマ区切り可)またはIP配列
	 * @return bool true=収まっている false=収まっていない
	 */
	static public function checkIp($inIP,$targetIP) {
		$ret = FALSE;
		if( empty($inIP) ) return FALSE;
		if( empty($targetIP) ) return TRUE; // ターゲットIPが指定されなかったら正とする
		// ターゲットが配列かどうか調査して配列でなかったら配列にする
		$ipList = array();
		if( is_array($targetIP) == FALSE ){
			$ipList = explode(',',$targetIP);
		} else {
			$ipList = $targetIP;
		}
		// IP配列を調査
		foreach($ipList as $ip) {
			$ip = trim($ip);
			$ret = self::inCIDR($inIP,$ip);
			if( $ret ) break;
		}
		return $ret;
	}

	/**
	 * 指定のIPがCIDR範囲に収まっているかチェックする
	 * @param string 比較IP
	 * @param string CIDR表記のアドレス
	 * @return bool true=収まっている false=収まっていない
	 */
	static public function inCIDR($inIP,$cidr) {
		$ret = FALSE;
		if( strpos($cidr,"/") !== FALSE ) {
			// CIDR表記だった場合
			list($network, $mask_bit_len) = explode('/', $cidr);
			$host = 32 - $mask_bit_len;
			$net = ip2long($network) >> $host << $host;
			$ip_net = ip2long($inIP) >> $host << $host;
			if( $net === $ip_net ) {
				$ret = TRUE;
			} else {
				$ret = FALSE;
			}
		} else {
			// 単純IP表記だった場合
			if( $inIP == $cidr ) {
				$ret = TRUE;
			}
		}
		return $ret;
	}
}
?>
