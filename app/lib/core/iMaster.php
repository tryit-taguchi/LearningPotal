<?PHP
/**
 * 汎用マスターデータ配列設定
 *
 * @access public
 * @author Atsushi Taguchi
 * @copyright Copyright (c) 2013, Tryit
 * @version 1.00
 * @since 2013/07/01
 */
/**
 */
$iMaster = array (
	'M_USER' => 
		array(
			'TYPE' => '',
		),
	'M_COUNT' => 
		array(
			'COUNT_TYPE' =>
				array(1=>'',2=>''),
		),
);
// 上記のマスターを引き出す関数
function getMasterData($leyer1=null,$leyer2=null,$leyer3=null) {
	global $iMaster;
	if( empty($leyer1) ) return $iMaster;
	if( empty($leyer2) ) return $iMaster[$leyer1];
	if( empty($leyer3) ) return $iMaster[$leyer1][$leyer2];
	return $iMaster[$leyer1][$leyer2][$leyer3];
}
?>
