<?PHP
/**
 * RestAPI用クラス(静的クラス)
 *
 * RestAPI用クラス
 *
 * @access public
 * @author Atsushi Taguchi
 * @copyright Copyright (c) 2018, Tryit
 * @version 1.00
 * @since 2018/09/05
 */
/**
 */
class cRest
{
	/**
	 * コンストラクタ
	 */
	function __construct() {
	}

	/**
	 * 失敗レスポンス
	 * @param String キー
	 * @return boolian
	 */
	static public function error($data,$message = "error") {
		$timeStamp = time();
		$dateStr   = date('Y-m-d H:i:s',$timeStamp);
		$response = array(
			'status'     => 'error',
			'message'    => $message,
			'update'     => $dateStr,
			'updatestamp'=> $timeStamp,
			'data'       => $data,
		);
		echo json_encode($response);
		exit;
	}

	/**
	 * 成功レスポンス
	 * @param String キー
	 * @return boolian
	 */
	static public function success($data,$message = "success") {
		$timeStamp = time();
		$dateStr   = date('Y-m-d H:i:s',$timeStamp);
		$response = array(
			'status'     => 'success',
			'message'    => $message,
			'update'     => $dateStr,
			'updatestamp'=> $timeStamp,
			'data'       => $data,
		);
//fLog($data);
		$json = json_encode($response);
		echo $json;
		exit;
	}
}
?>
