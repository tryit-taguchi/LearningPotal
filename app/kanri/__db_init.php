<?php
/**
 * DB初期化
 *
 * DB初期化をします
 *
 * @access public
 * @author Atsushi Taguchi
 * @copyright Copyright (c) 2017, Tryit
 * @version 1.00
 * @since 2017/05/02
 */
/**
 */
require(".site_conf.php");
require("lib/core/iCommon.php");            // 基本クラス
require(".page_common.php");

//--------------------------------------
// 外部データの取得
//--------------------------------------

//--------------------------------------
// 内部データの取得
//--------------------------------------
$db = new cDbExt();
//--------------------------------------
// コントローラー
//--------------------------------------
$checkFileName = "init/check.dat";
if( !file_exists("init/check.dat") ) {
	$sql = "TRUNCATE TABLE m_config";
	$res = $db->exec($sql);
	$nowDt = date("Y-m-d H:i:s");
	$nowY = date("Y");
	$sql = "INSERT INTO m_config (CKEY, CVAL, CNOTE, INS_DT, UPD_DT, DEL_FLG) VALUES ('研修名', '勉強会{$nowY}', '研修名の設定', '{$nowDt}', '{$nowDt}', '0')";
	$res = $db->exec($sql);
	$sql = "INSERT INTO m_config (CKEY, CVAL, CNOTE, INS_DT, UPD_DT, DEL_FLG) VALUES ('管理メモ', '勉強会メモ', '管理メモ', '{$nowDt}', '{$nowDt}', '0')";
	$res = $db->exec($sql);

	$sql = "TRUNCATE TABLE m_question";
	$res = $db->exec($sql);

	$sql = "TRUNCATE TABLE m_member";
	$res = $db->exec($sql);

	$sql = "TRUNCATE TABLE t_answer";
	$res = $db->exec($sql);

	$sql = "TRUNCATE TABLE t_catchphrase";
	$res = $db->exec($sql);

	$sql = "TRUNCATE TABLE t_comment";
	$res = $db->exec($sql);

	$sql = "TRUNCATE TABLE t_faq";
	$res = $db->exec($sql);

	file_put_contents($checkFileName,"check");
	
	if( $res == true ) {
		echo "DB初期化 OK.";
	} else {
		echo "DB初期化 NG.";
	}
} else {
	echo "DB初期化できません。";
}
?>
