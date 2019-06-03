<?PHP
/**
 * 会場用クラス(静的クラス)
 *
 * 会場用クラス
 *
 * @access public
 * @author Atsushi Taguchi
 * @copyright Copyright (c) 2018, Tryit
 * @version 1.00
 * @since 2018/09/05
 */
/**
 */
class cSite
{
	/**
	 * 定数
	 */
	public $data;

	/**
	 * コンストラクタ
	 */
	function __construct() {
	}

	/**
	 * データ取得
	 * @param String $lectureName
	 * @param String $questionType
	 * @return void 無し
	 */
	static public function getData($lectureName) {
		$cond = array();
		$cond['LECTURE_NAME']  = $lectureName;
		$list = cMasterSite::getList($cond,"SITE_NO");
		return $list;
	}

	/**
	 * 保存
	 * @param String $lectureName
	 * @param String $questionType
	 * @return void 無し
	 */
	static public function saveData($lectureName,$form) {
		$cnt = 0;
		foreach($form['s'] as $rec) {
			if( !empty($rec['SITE_NAME']) ) {
				$rec['LECTURE_NAME']    = $lectureName;  // 講座名
				$id = cMasterSite::setData($rec);
				$cnt++;
			} else {
				// 会場が入っていなかったら削除する
				$cond = array();
				$cond['SITE_NO']       = $rec['SITE_NO']; // 会場名
				$cond['LECTURE_NAME']  = $lectureName;
				cMasterSite::logDelData($cond);
			}
		}
		$result = new StdClass();
		$result->status = "success";
		$result->result = new StdClass();
		$result->result->lastUpdate = cDate::now();
		$result->result->form = $form;
		echo json_encode($result);
		exit;
	}
}
?>
