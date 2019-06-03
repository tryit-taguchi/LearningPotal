<?php
/**
 * グラフの保存
 *
 * グラフの保存をします
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

$upload_data=$_POST['upload_data'];
$file_name=$_POST['file_name'];
$new_file_name="temp/".$file_name.".png";
$fp = fopen($new_file_name,'w');
fwrite($fp,base64_decode($upload_data));
fclose($fp);

echo "OK.";
?>
