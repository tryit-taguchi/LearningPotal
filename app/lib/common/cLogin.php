<?PHP
/**
 * LOGIN用クラス(静的クラス)
 *
 * LOGIN用クラス
 *
 * @access public
 * @author Atsushi Taguchi
 * @copyright Copyright (c) 2018, Tryit
 * @version 1.00
 * @since 2018/09/05
 */
/**
 */
class cLogin
{
	/**
	 * コンストラクタ
	 */
	function __construct() {
	}

	/**
	 * ログイン状態取得
	 * @param String キー
	 * @return boolian
	 */
	static public function isLogin() {
		if( empty($_SESSION['member']['TERM_CD']) ) {
			//Redirect("frame.php?page=".urlencode("login.php?dt=".date("YmdHis"))."");
			Redirect("login.php?dt=".date("YmdHis"));
			return false;
		}
		if( !DEBUG_MODE ) { // 本番サーバの場合は本日の日付の受講者のみ対象
			if( $_SESSION['member']['LECTURE_DT'] != date("Y-m-d") ) {
				Redirect("login.php?dt=".date("YmdHis").'&cd='.$_SESSION['member']['TERM_CD']);
				return false;
			}
		}
		return true;
	}

	/**
	 * ログイン状態セット
	 * @param String キー
	 * @return null
	 */
	static public function setLogin() {
		global $REQUEST;
		$seatCd = strtoupper($REQUEST->post['SEAT_CD']);
		if( empty($seatCd) ) {
			cRest::error(null);
		}
		$cond = array();
		$cond['SEAT_CD']      = $seatCd;
		if( $seatCd[0] != '#' ) {
			$cond['LECTURE_DT']   = date('Y-m-d');
			$cond['LECTURE_NAME'] = cConfig::getValue("研修名");
		}
		$data = cMasterMember::getData($cond);
		if( empty($data) ) {
			cRest::error(null);
		}
		$data['UPD_DT'] = date("Y-m-d H:i:s"); // ログイン時間更新
		cMasterMember::setData($data);
		cRest::success($data);
		return;
	}

	/**
	 * ログアウト
	 * @param 無し
	 * @return boolian
	 */
	static public function logOut() {
		//$_SESSION['member'] = array();
		$_SESSION = array();
		cookieSet('LOGIN_DATE','');	 // 承認画面用クッキーをクリアする
		return true;
	}

	/**
	 * 講師かどうかのチェック
	 * @param 無し
	 * @return boolian
	 */
	static public function isLecturer() {
		if( $_SESSION['member']['TERM_CD'][0] == 'K' ) {
			return true;
		} else {
			return false;
		}
	}

}
?>
