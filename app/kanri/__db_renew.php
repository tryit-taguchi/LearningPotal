<?php
/**
 * DB更新
 *
 * DB更新をします
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
$sql = "ALTER TABLE `m_question` ADD `QUESTION_SHORT_STR` VARCHAR(50) NULL DEFAULT NULL COMMENT '略称' AFTER `QUESTION_STR`";
$res = $db->exec($sql);

$sql = "ALTER TABLE `m_member` ADD `SITE_NAME` VARCHAR(50) NULL DEFAULT NULL COMMENT '会場名' AFTER `COMPANY_CD`";
$res = $db->exec($sql);

$sql = "ALTER TABLE `m_member` ADD `LECTURE_TYPE` TINYINT NULL DEFAULT '1' COMMENT 'グループ　1=前半 2=後半' AFTER `LECTURE_DT`";
$res = $db->exec($sql);

$sql = "ALTER TABLE `m_member` ADD `DIV_NAME` VARCHAR(50) NULL DEFAULT NULL COMMENT '部署名' AFTER `PLACE_NAME`";
$res = $db->exec($sql);

$sql = "ALTER TABLE `m_member` ADD `POST_NAME` VARCHAR(50) NULL DEFAULT NULL COMMENT '役職' AFTER `DIV_NAME`";
$res = $db->exec($sql);

$sql = "ALTER TABLE `m_member` CHANGE `LECTURE_CD` `SEAT_CD` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'X00' COMMENT '席コード'";
$res = $db->exec($sql);

if( $res == true ) {
	echo "OK.";
} else {
	echo "NG.";
}
?>
