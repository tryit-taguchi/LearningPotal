<?php
/**
 * 勉強会QA返答
 *
 * 勉強会QA返答をします
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
$nextProcess    = strim($REQUEST->val["nextProcess"]);
$reqVal         = strim($REQUEST->val["val"]);

//--------------------------------------
// 内部データの取得
//--------------------------------------
$lectureName  = cConfig::getValue("研修名");
$faqImpLast = "勉強会QA-登録_最終更新日時";
$faqExtLast = "勉強会QA-出力_最終更新日時";
$db = new cDbExt();
//--------------------------------------
// コントローラー
//--------------------------------------
switch($nextProcess) {
	default:
		$form = array();
		$form['lastUpdate'] = cConfig::getValue($faqImpLast);
		defaultTemplate($form); // php と主ファイル名が同じhtmlをtemplate/からインクルードする
		break;
	case "dataImport":
		if($_FILES["file"]["tmp_name"]){
			$result = new StdClass();
			// アップロードされたファイルの取得
			list($file_name,$file_type) = explode(".",$_FILES['file']['name']);
			if( $file_type != "xlsx" ) {
				$result->status = "error";
				$result->result = new StdClass();
				$result->result->errorMessage = "EXCEL以外のファイルが指定されています。";
				echo json_encode($result);
				exit;
			}
			// 更新日とラスト出力日の比較
			$lastExt = cConfig::getValue($faqExtLast);
			$data = $db->getData("select max(INS_DT) as LAST_INS_DT from T_FAQ");
			$lastQ  = $data['LAST_INS_DT'];
			if( !empty($lastExt) ) {
				if( $lastExt < $lastQ ) {
					$result->status = "error";
					$result->result = new StdClass();
					$result->result->errorMessage = "[{$lastQ}] [{$lastExt}] 新しい質問が入っていますので、先に出力してください。";
					echo json_encode($result);
					exit;
				}
			}
			$db->exec("delete from T_FAQ where LECTURE_NAME='{$lectureName}'"); // データを入れ替えるので該当勉強会のデータをクリア
			$catArr = array();
			cExcel::load($_FILES["file"]["tmp_name"]);
			$srcArr = cExcel::toArray();

			// ファイルチェック
			if( $srcArr[1]['A'] != "勉強会QA" ) {
				$result->status = "error";
				$result->result = new StdClass();
				$result->result->errorMessage = "勉強会QAではないEXCELファイルです。";
				echo json_encode($result);
				exit;
			}

			// DBに収納する
			$row = 0;
			$faqPid = 0;
			foreach($srcArr as $line) {
				$row++;
				if( $row <= 2 ) continue; // ヘッダは無視する
				$data = array();
				$data['FAQ_CAT']          = strim($line['A']); // カテゴリ―
				$data['FAQ_TYPE']         = strim($line['B']); // 種別
				$data['Q_STR']            = strim($line['C']); // 質問
				$data['A_STR']            = strim($line['D']); // 回答
				$data['FAQ_FLG']          = strim($line['E'])==''?0:1; // FAQ
				$data['LECTURE_NAME']     = $lectureName;
				if( $data['FAQ_TYPE'] == '追加' ) {
					$data['FAQ_PID']          = $faqPid; // 追加の場合は親ID
				} else {
					$data['FAQ_PID']          = 0; // 主の場合は0
				}
				$catArr[$data['FAQ_CAT']] = $data['FAQ_CAT'];
				
				// 必須項目が入っていないデータは除く（カテゴリ―）
				if( $data['FAQ_CAT'] == '' ) {
					continue;
				}
				$id = cTransactionFaq::setData($data);
				if( $data['FAQ_TYPE'] == '主' ) {
					$faqPid = $id;
				}
			}
			cConfig::setData("FAQカテゴリー",json_encode($catArr));

			cConfig::setData($faqImpLast,cDate::now());
			$result = new StdClass();
			$result->status = "success";
			$result->result = new StdClass();
			$result->result->lastUpdate = cConfig::getValue($faqImpLast);
			echo json_encode($result);
			exit;
		}
		break;
	case "dataExport":
		cConfig::setData($faqExtLast,cDate::now());
		$catJson = cConfig::getValue("FAQカテゴリー");
		$catArr = json_decode($catJson);

		// ツリー構造のデータ作成
		$cond = array();
		$cond['LECTURE_NAME']  = $lectureName;
		$faqList = array();
		foreach($catArr as $cat) {
			$faqList[$cat] = array();
		}
		$list = cTransactionFaq::getList($cond,"FAQ_PID,FAQ_ID");
//dout($list);
		foreach($list as $rec) {
			if( $rec['FAQ_TYPE'] == "主" ) {
				$faqList[$rec['FAQ_CAT']][$rec['FAQ_ID']] = $rec;
				$ref = &$rec;
			}
			if( $rec['FAQ_TYPE'] == "追加" ) {
				$faqList[$rec['FAQ_CAT']][$rec['FAQ_PID']]['ADD'][] = $rec;
			}
		}
//fLog($faqList);
//dout($faqList);
//exit;
		// ツリーデータからExcelに展開
		cExcel::load("template/excel/faq.xlsx");
		$no = 3;
		foreach($catArr as $cat) {
			if( !empty($faqList[$cat]) ) {
				foreach($faqList[$cat] as $prec) {
					if( !empty($cat) ) {
						cExcel::cell( 1, $no,$cat);
						cExcel::cell( 2, $no,$prec['FAQ_TYPE']);
						cExcel::cell( 3, $no,$prec['Q_STR']);
						cExcel::cell( 4, $no,$prec['A_STR']);
						cExcel::cell( 5, $no,$prec['FAQ_FLG']==1?'●':'');
						$no++;
					}
					foreach($prec['ADD'] as $crec) {
						if( !empty($cat) ) {
							cExcel::cell( 1, $no,$cat);
							cExcel::cell( 2, $no,$crec['FAQ_TYPE']);
							cExcel::cell( 3, $no,$crec['Q_STR']);
							cExcel::cell( 4, $no,$crec['A_STR']);
							cExcel::cell( 5, $no,$crec['FAQ_FLG']==1?'●':'');
							$no++;
						}
					}
				}
			} else {
				if( !empty($cat) ) {
					cExcel::cell( 1, $no,$cat);
					cExcel::cell( 2, $no,"");
					cExcel::cell( 3, $no,"");
					cExcel::cell( 4, $no,"");
					cExcel::cell( 5, $no,"");
					$no++;
				}
			}
		}
		$tmpDir = sys_get_temp_dir();
		$exceiFileName = "勉強会QA_".date("YmdHis").".xlsx";
		cExcel::save($tmpDir."/{$exceiFileName}");
		// ダウンロードさせる
		cUtility::download($tmpDir."/{$exceiFileName}",$exceiFileName);

		break;
}
?>
