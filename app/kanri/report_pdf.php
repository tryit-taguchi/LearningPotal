<?php
/**
 * レポート出力表示
 *
 * レポート出力表示をします
 *
 * @access public
 * @author Atsushi Taguchi
 * @copyright Copyright (c) 2017, Tryit
 * @version 1.00
 * @since 2017/05/02
 */
/**
cd \xampp\htdocs\nsp\kanri\
http://localhost/nsp/kanri/report_pdf?lectureAtr=2018-11-15@十勝スピードウェイ@1
 */
require(".site_conf.php");
require("lib/core/iCommon.php");            // 基本クラス
require(".page_common.php");


//--------------------------------------
// ブロック処理
//--------------------------------------

//--------------------------------------
// 外部データの取得
//--------------------------------------
$form = $REQUEST->val;
$lectureAtr = $form['lectureAtr'];
$tmp = explode('@',$lectureAtr);
$lectureDt = $tmp[0];
$siteName  = $tmp[1];
$lectureType = (int)$tmp[2];
$termCd    = $form['termCd'];
$nameWord  = $form['nameWord'];
$siteId    = hash('ripemd160',$lectureDt.$siteName);
$dir = getcwd();
$lectureAtrEnc = urlencode($lectureAtr);
$nameWordEnc   = urlencode($nameWord);

$lectureTypeStr = $lectureType==1?'前半':'後半';
$pdfFileName = "{$lectureDt}_{$siteName}_{$lectureTypeStr}";

$urlDirName = getUrlDir();
$command = $dir."\\wkhtmltopdf.exe --page-size A3 --margin-top 5 --margin-right 0 --margin-left 5 --margin-bottom 0 --disable-smart-shrinking --orientation Landscape  \"http://localhost{$urlDirName}/report_view.php?lectureAtr={$lectureAtrEnc}&termCd={$termCd}&nameWord={$nameWordEnc}&lectureType={$lectureType}\" ./pdf/{$pdfFileName}.pdf";


$command = mb_convert_encoding($command,'SJIS-win','UTF-8');
exec($command,$output);
print_r($output);

?>
