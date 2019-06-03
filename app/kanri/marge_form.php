<?php
/**
 * データマージ
 *
 * データマージをします
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
$margeImpLast = "データマージ-登録_最終更新日時";
$margeExtLast = "データマージ-出力_最終更新日時";
$lastUpdate = cConfig::getValue($margeImpLast);

$errorMessage = array();
$resultMessage = array();
$db = new cDbExt();
//--------------------------------------
// コントローラー
//--------------------------------------
set_time_limit(0);
switch($nextProcess) {
	default:
		break;
	case "dataImport":
		$ret = firstProcess($_FILES["dataFile"]["tmp_name"]); // データ展開処理　テンポラリに展開
		if( $ret ) {
			$ret = secondProcess(); // 受講者データとマージしたファイルをテンポラリに出力
		} else {
			$errorMessage[] = "処理エラー (code:1)";
		}
		if( $ret ) {
			$ret = thirdProcess(); // 受講者データをDBに投入
		} else {
			$errorMessage[] = "処理エラー (code:2)";
		}
		if( $ret ) {
			$ret = fourthProcess(); // コメント・キャッチ・回答をDBに投入
		} else {
			$errorMessage[] = "処理エラー (code:3)";
		}
		$ret = true;
		cConfig::setData($margeImpLast,cDate::now());
		break;
	case "dataExport":
		$filename = "export_".date('Y-m-d').".nlp";
		exportProcess($filename);
		Redirect("marge_form.php");
		exit;
		break;
}
$form = array();
$form['lastUpdate'] = cConfig::getValue($margeImpLast);
defaultTemplate($form); // php と主ファイル名が同じhtmlをtemplate/からインクルードする
exit;
// -------------------------------------
// 関数
// -------------------------------------

// コメント・キャッチ・回答をDBに投入
function fourthProcess() {
	global $errorMessage;
	global $resultMessage;
	global $lectureName;
	$hnd_src = fopen("./temp/cm2.txt", "r");
	if( !$hnd_src  ) { $errorMessage[] = "テンポラリファイルが開けません。 (code:41)"; return false; }
	$pcnt = 0;
	while (($buffer = fgets($hnd_src, 4096)) !== false) {
		$arr = explode(",",trim($buffer));
		$memberId = getFourthProcessMemberId($arr);
		if( $memberId == 0 ) continue;
		$data = array();
		$data['COMMENT_DT']    = $arr[5];    // 日程
		$data['COMMENT_TYPE']  = $arr[6];    // コメントタイプ
		$data['MEMBER_ID']     = $memberId;  // 受講者ID
		$data['COMMENT_STR']   = str_replace("<br>","\n",$arr[8]);    // コメントテキスト
		$data['LECTURE_NAME']  = $lectureName;
		$data['INS_DT']        = $arr[9];    // 挿入日時
		$cond = array();
		$cond['COMMENT_DT']    = $data['COMMENT_DT'];
		$cond['COMMENT_TYPE']  = $data['COMMENT_TYPE'];
		$cond['MEMBER_ID']     = $data['MEMBER_ID'];
		$cond['INS_DT']        = $data['INS_DT'];
		$chkData = cTransactionComment::getData($cond);
		if( empty($chkData['COMMENT_ID']) ) {
			cTransactionComment::setData($data);
			$pcnt++;
		} else {
			//$errorMessage[] = $data['COMMENT_DT'] . " : 「" . $data['COMMENT_STR'] . "」 はインポートしませんでした。 {$memberId} ".$data['COMMENT_TYPE']."";
		}
	}
	$resultMessage[] = "{$pcnt}件のコメントをインポートしました。";
	fclose($hnd_src);

	$hnd_src = fopen("./temp/ct2.txt", "r");
	if( !$hnd_src  ) { $errorMessage[] = "テンポラリファイルが開けません。 (code:42)"; return false; }
	$pcnt = 0;
	while (($buffer = fgets($hnd_src, 4096)) !== false) {
		$arr = explode(",",trim($buffer));
		$memberId = getFourthProcessMemberId($arr);
		if( $memberId == 0 ) continue;
		$data = array();
		$data['CATCHPHRASE_DT']  = $arr[5];    // 日程
		$data['MEMBER_ID']     = $memberId;  // 受講者ID
		$data['CATCHPHRASE_STR'] = str_replace("<br>","\n",$arr[7]);    // テキスト
		$data['VOTE_CNT']      = $arr[8];    // 投票数
		$data['LECTURE_NAME']  = $lectureName;
		$data['INS_DT']        = $arr[9];    // 挿入日時
		$cond = array();
		$cond['CATCHPHRASE_DT']  = $data['CATCHPHRASE_DT'];
		$cond['MEMBER_ID']     = $data['MEMBER_ID'];
		$cond['INS_DT']        = $data['INS_DT'];
		$chkData = cTransactionCATCHPHRASE::getData($cond);
		if( empty($chkData['CATCHPHRASE_ID']) ) {
			cTransactionCATCHPHRASE::setData($data);
			$pcnt++;
		} else {
			//$errorMessage[] = $data['CATCHPHRASE_DT'] . " : 「" . $data['CATCHPHRASE_STR'] . "」 がインポートできませんでした。";
		}
	}
	$resultMessage[] = "{$pcnt}件のキャッチフレーズをインポートしました。";
	fclose($hnd_src);

	$hnd_src = fopen("./temp/a2.txt", "r");
	if( !$hnd_src  ) { $errorMessage[] = "テンポラリファイルが開けません。 (code:43)"; return false; }
	$pcnt = 0;
	while (($buffer = fgets($hnd_src, 4096)) !== false) {
		$arr = explode(",",trim($buffer));
		$memberId = getFourthProcessMemberId($arr);
		$data = array();
		$data['ANSWER_DT']     = $arr[5];
		$data['ANSWER_TYPE']   = $arr[6];
		$data['MEMBER_ID']     = $memberId;  // 受講者ID
		$data['QUESTION_ID']   = $arr[8];
		$data['ANSWER_0']      = $arr[9];
		$data['ANSWER_1']      = $arr[10];
		$data['ANSWER_2']      = $arr[11];
		$data['ANSWER_3']      = $arr[12];
		$data['ANSWER_4']      = $arr[13];
		$data['ANSWER_5']      = $arr[14];
		$data['ANSWER_6']      = $arr[15];
		$data['ANSWER_7']      = $arr[16];
		$data['ANSWER_8']      = $arr[17];
		$data['ANSWER_9']      = $arr[18];
		$data['INS_DT']        = $arr[19];    // 挿入日時
		$cond = array();
		$cond['ANSWER_DT']     = $data['ANSWER_DT'];
		$cond['ANSWER_TYPE']   = $data['ANSWER_TYPE'];
		$cond['MEMBER_ID']     = $data['MEMBER_ID'];
		$cond['QUESTION_ID']   = $data['QUESTION_ID'];
		$chkData = cTransactionAnswer::getData($cond);
		if( empty($chkData['ANSWER_ID']) ) {
			cTransactionAnswer::setData($data);
			$pcnt++;
		} else {
			//$errorMessage[] = $data['ANSWER_DT'] . " : 「" . $data['QUESTION_ID'] . "」 がインポートできませんでした。";
		}
	}
	$resultMessage[] = "{$pcnt}件の回答をインポートしました。";
	fclose($hnd_src);
	return true;
}

// 受講者IDを取得
function getFourthProcessMemberId($arr) {
	global $errorMessage;
	$data = array();
	$data['TERM_CD']      = $arr[0]; // 利用端末
	$data['LECTURE_DT']   = $arr[1]; // 日程
	$data['SITE_NAME']    = $arr[2]; // 会場
	$data['LECTURE_TYPE'] = $arr[3]; // 前半・後半
	$cond = array();
	$cond['TERM_CD']      = $data['TERM_CD'];
	$cond['LECTURE_DT']   = $data['LECTURE_DT'];
	$cond['SITE_NAME']    = $data['SITE_NAME'];
	$cond['LECTURE_TYPE'] = $data['LECTURE_TYPE'];
	$chkData = cMasterMember::getData($cond);
	if( !empty($chkData['MEMBER_ID']) ) {
		return $chkData['MEMBER_ID'];
	} else {
		$errorMessage[] =  "存在しない受講者です。「" . $data['TERM_CD'] . " / " . $data['LECTURE_DT'] . " / " . $data['SITE_NAME'] . " / " . $data['LECTURE_TYPE'] . "」";
		return 0;
	}
}

// 受講者データをDBに投入
function thirdProcess() {
	global $errorMessage;
	global $resultMessage;
	global $lectureName;
	$hnd_src = fopen("./temp/mb.txt", "r");
	if( !$hnd_src  ) { $errorMessage[] = "テンポラリファイルが開けません。 (code:31)"; return false; }
	$pcnt = 0;
	while (($buffer = fgets($hnd_src, 4096)) !== false) {
		$arr = explode(",",trim($buffer));
		$data = array();
		$data['SEAT_CD']      = $arr[1];  // 席番CD
		$data['TERM_CD']      = $arr[2];  // 端末CD
		$data['GROUP_CD']     = $arr[3];  // グループCD
		$data['LECTURE_DT']   = $arr[4];  // 日程
		$data['SITE_NAME']    = $arr[5];  // 会場
		$data['LECTURE_TYPE'] = $arr[6];  // 前半・後半
		$data['MEMBER_NAME']  = $arr[7];  // 利用者名
		$data['COMPANY_NAME'] = $arr[8];  // 法人名
		$data['PLACE_NAME']   = $arr[9];  // 店舗
		$data['INS_DT']       = $arr[10]; // 挿入日時
		// 必須項目が入っていないデータは除く（端末・日付・利用者名）
		if( $data['SEAT_CD'] == '' 
		 || $data['LECTURE_DT'] == '' 
		 || $data['COMPANY_NAME'] == '' 
		 || $data['MEMBER_NAME'] == ''
		 || $data['LECTURE_TYPE'] == 0 ) {
			continue;
		}
		// レコードに（端末・日付・会場）が既に割り当てられているかチェックし、割り当てられていたら上書きする
		$cond = array();
		$cond['SEAT_CD']      = $data['SEAT_CD'];
		$cond['LECTURE_DT']   = $data['LECTURE_DT'];
		$cond['SITE_NAME']    = $data['SITE_NAME'];
		$cond['LECTURE_TYPE'] = $data['LECTURE_TYPE'];
		$chkData = cMasterMember::getData($cond);
		if( empty($chkData['MEMBER_ID']) ) {
			// DB上に受講者がいなかったら挿入する
			cMasterMember::setData($data);
			$pcnt++;
		}
	}
	$resultMessage[] = "{$pcnt}名の受講者をインポートしました。";
	fclose($hnd_src);
	return true;
}

// 受講者データとマージ
function secondProcess() {
	global $errorMessage;
	$hnd_src = fopen("./temp/mb.txt", "r");
	if( !$hnd_src  ) { $errorMessage[] = "テンポラリファイルが開けません。 (code:21)"; return false; }

	$memberList = array();
	while (($buffer = fgets($hnd_src, 4096)) !== false) {
		$arr = explode(",",trim($buffer));
		$out = array();
		$out[] = $arr[1];
		$out[] = $arr[2];
		$out[] = $arr[3];
		$out[] = $arr[4];
		$out[] = $arr[5];
		$memberList[$arr[0]] = join(",",$out);
	}
	fclose($hnd_src);
	// マージ
	//dout($memberList);
	$hnd_dst = fopen("./temp/cm2.txt", "w");
	$hnd_src = fopen("./temp/cm.txt", "r");
	if( !$hnd_src  ) { $errorMessage[] = "テンポラリファイルが開けません。 (code:22)"; return false; }
	while (($buffer = fgets($hnd_src, 4096)) !== false) {
		$arr = explode(",",trim($buffer));
		$memberId = $arr[2];
		$line = $memberList[$memberId] . "," . trim($buffer);
		fputs($hnd_dst,$line."\n");
	}
	fclose($hnd_src);
	fclose($hnd_dst);

	$hnd_dst = fopen("./temp/ct2.txt", "w");
	$hnd_src = fopen("./temp/ct.txt", "r");
	if( !$hnd_src  ) { $errorMessage[] = "テンポラリファイルが開けません。 (code:23)"; return false; }
	while (($buffer = fgets($hnd_src, 4096)) !== false) {
		$arr = explode(",",trim($buffer));
		$memberId = $arr[1];
		$line = $memberList[$memberId] . "," . trim($buffer);
		fputs($hnd_dst,$line."\n");
	}
	fclose($hnd_src);
	fclose($hnd_dst);

	$hnd_dst = fopen("./temp/a2.txt", "w");
	$hnd_src = fopen("./temp/a.txt", "r");
	if( !$hnd_src  ) { $errorMessage[] = "テンポラリファイルが開けません。 (code:24)"; return false; }
	while (($buffer = fgets($hnd_src, 4096)) !== false) {
		$arr = explode(",",trim($buffer));
		$memberId = $arr[2];
		$line = $memberList[$memberId] . "," . trim($buffer);
		fputs($hnd_dst,$line."\n");
	}
	fclose($hnd_src);
	fclose($hnd_dst);
	return true;
}

// データ展開処理
function firstProcess($fileName) {
	global $errorMessage;
	if(!$_FILES["dataFile"]["tmp_name"]) return false;
	list($file_name,$file_type) = explode(".",$_FILES['dataFile']['name']);
	if( $file_type != "nlp" ) {
		$errorMessage[] = "指定のファイルがマージ用ではありません。拡張子が「nlp」のファイルを使用してください。";
		return false;
	}

	// アップロードファイルを開き、種別毎にファイルを分ける
	$hnd_mb  = fopen("./temp/mb.txt", "w");
	$hnd_cm  = fopen("./temp/cm.txt", "w");
	$hnd_ct  = fopen("./temp/ct.txt", "w");
	$hnd_a   = fopen("./temp/a.txt",  "w");
	$hnd_src = fopen($fileName, "r");
	$c1 = $c2 = $c3 = $c4 = $c5 = $c6;
	while (($buffer = fgets($hnd_src, 4096)) !== false) {
		$arr = explode(",",trim($buffer));
		switch($arr[0]) {
			case "M": // 受講者
				$out = array();
				$out[] = $arr[1];
				$out[] = $arr[2];
				if( $arr[3] != '@' ) {
					$out[] = $c1 = $dt = '20'.substr($arr[3],0,2)."-".substr($arr[3],2,2)."-".substr($arr[3],4,2);
				} else {
					$out[] = $c1;
				}
				if( $arr[4] != '@' ) {
					$out[] = $c2 = $arr[4];
				} else {
					$out[] = $c2;
				}
				$out[] = $arr[5];
				$out[] = $arr[6];
				$out[] = $arr[7];
				if( $arr[8] != '@' ) {
					$out[] = $c3 = $arr[8];
				} else {
					$out[] = $c3;
				}
				$out[] = $arr[9];
				if( $arr[10] != '@' ) {
					$out[] = $c4 = $arr[10];
				} else {
					$out[] = $c4;
				}
				fputs($hnd_mb,join(",",$out)."\n");
				break;
			case "C": // コメント
				$out = array();
				if( $arr[1] != '@' ) {
					$out[] = $c1 = $dt = '20'.substr($arr[1],0,2)."-".substr($arr[1],2,2)."-".substr($arr[1],4,2);
				} else {
					$out[] = $c1;
				}
				$out[] = $arr[2];
				$out[] = $arr[3];
				$out[] = $arr[4];
				$out[] = $dt . " " . substr($arr[5],0,2).":".substr($arr[5],2,2).":".substr($arr[5],4,2);
				fputs($hnd_cm,join(",",$out)."\n");
				break;
			case "F": // キャッチ
				$out = array();
				if( $arr[1] != '@' ) {
					$out[] = $c1 = $dt = '20'.substr($arr[1],0,2)."-".substr($arr[1],2,2)."-".substr($arr[1],4,2);
				} else {
					$out[] = $c1;
				}
				$out[] = $arr[2];
				$out[] = $arr[3];
				$out[] = $arr[4];
				$out[] = $dt . " " . substr($arr[5],0,2).":".substr($arr[5],2,2).":".substr($arr[5],4,2);
				fputs($hnd_ct,join(",",$out)."\n");
				break;
			case "A":  // 回答
				$ans = $buffer;
				$ans = str_replace("#9","0,0,0,0,0,0,0,0,0,",$ans);
				$ans = str_replace("#8","0,0,0,0,0,0,0,0,",$ans);
				$ans = str_replace("#7","0,0,0,0,0,0,0,",$ans);
				$ans = str_replace("#6","0,0,0,0,0,0,",$ans);
				$ans = str_replace("#5","0,0,0,0,0,",$ans);
				$ans = str_replace("#4","0,0,0,0,",$ans);
				$ans = str_replace("#3","0,0,0,",$ans);
				$ans = str_replace("#2","0,0,",$ans);
				$arr = explode(",",trim($ans));

				$out = array();
				if( $arr[1] != '@' ) {
					$out[] = $c1 = $dt = '20'.substr($arr[1],0,2)."-".substr($arr[1],2,2)."-".substr($arr[1],4,2);
				} else {
					$out[] = $c1;
				}
				$out[] = $arr[2];
				$out[] = $arr[3];
				$out[] = $arr[4];
				$out[] = $arr[5];
				$out[] = $arr[6];
				$out[] = $arr[7];
				$out[] = $arr[8];
				$out[] = $arr[9];
				$out[] = $arr[10];
				$out[] = $arr[11];
				$out[] = $arr[12];
				$out[] = $arr[13];
				$out[] = $arr[14];
				if( $arr[15] != '@' ) {
					$out[] = $c2 = $dt . " " . substr($arr[15],0,2).":".substr($arr[15],2,2).":".substr($arr[15],4,2);
				} else {
					$out[] = $c2;
				}
				fputs($hnd_a,join(",",$out)."\n");
				break;
		}
		$old = $arr;
//				echo $buffer;
	}
	fclose($hnd_src);
	fclose($hnd_a);
	fclose($hnd_ct);
	fclose($hnd_cm);
	fclose($hnd_mb);
	return true;
}

// エクスポート処理
function exportProcess($filename) {
	$db = new cDbExt();
	// ファイルタイプを指定
	header('Content-Type: text/plain');
	// ファイルのダウンロード、リネームを指示
	header('Content-Disposition: attachment; filename="'.$filename.'"');
	
	$sql = "SELECT * from M_MEMBER order by MEMBER_ID";
	$db->select($sql);
	if( $db->rowCount() > 0 ) {
		$old = array();
		while ($row = $db->fetch() ) {
			$out = array();
			$out[] = $row['MEMBER_ID'];
			if( $row['SEAT_CD'][0] != '#' ) {
				$out[] = $row['SEAT_CD'];
				$out[] = $row['TERM_CD'];
				$out[] = $row['GROUP_CD'];
				$out[] = $old['LECTURE_DT']==$row['LECTURE_DT']?'@':date("ymd",strtotime($row['LECTURE_DT']));
				$out[] = $old['SITE_NAME']==$row['SITE_NAME']?'@':$row['SITE_NAME'];
				$out[] = $row['LECTURE_TYPE'];
				$out[] = $row['MEMBER_NAME'];
				$out[] = $old['COMPANY_NAME']==$row['COMPANY_NAME']?'@':$row['COMPANY_NAME'];
				$out[] = $row['PLACE_NAME'];
				$out[] = $old['INS_DT']==$row['INS_DT']?'@':$row['INS_DT'];
				if( !empty($row['LECTURE_DT']) ) {
					echo "M,",join(',',$out) . "\n";
				}
				$old = $row;
			}
		}
	}

	$sql = "SELECT * from T_COMMENT order by COMMENT_ID";
	$db->select($sql);
	if( $db->rowCount() > 0 ) {
		$old = array();
		while ($row = $db->fetch() ) {
			$out = array();
			//$out[] = $row['COMMENT_ID'];  // 省サイズのために省く
			$out[] = $old['COMMENT_DT']==$row['COMMENT_DT']?'@':date("ymd",strtotime($row['COMMENT_DT']));
			$out[] = $row['COMMENT_TYPE'];
			$out[] = $row['MEMBER_ID'];
			$out[] = str_replace(",","，",str_replace("\n","<br>",$row['COMMENT_STR']));
			//$out[] = $row['LECTURE_NAME']; // 省サイズのために省く
			$out[] = date("His",strtotime($row['INS_DT']));
			//$out[] = $row['UPD_DT'];  // 省サイズのために省く
			//$out[] = $row['DEL_FLG']; // 省サイズのために省く
			if( !empty($row['COMMENT_DT']) ) {
				echo "C,",join(',',$out) . "\n";
			}
			$old = $row;
		}
	}

	$sql = "SELECT * from T_CATCHPHRASE order by CATCHPHRASE_ID";
	$db->select($sql);
	if( $db->rowCount() > 0 ) {
		$old = array();
		while ($row = $db->fetch() ) {
			$out = array();
			//$out[] = $row['CATCHPHRASE_ID'];  // 省サイズのために省く
			$out[] = $old['CATCHPHRASE_DT']==$row['CATCHPHRASE_DT']?'@':date("ymd",strtotime($row['CATCHPHRASE_DT']));
			$out[] = $row['MEMBER_ID'];
			$out[] = str_replace(",","，",str_replace("\n","<br>",$row['CATCHPHRASE_STR']));
			$out[] = $row['VOTE_CNT'];
			//$out[] = $row['LECTURE_NAME'];// 省サイズのために省く
			$out[] = date("His",strtotime($row['INS_DT']));
			//$out[] = $row['UPD_DT'];  // 省サイズのために省く
			//$out[] = $row['DEL_FLG']; // 省サイズのために省く
			if( !empty($row['CATCHPHRASE_DT']) ) {
				echo "F,",join(',',$out) . "\n";
			}
			$old = $row;
		}
	}

	$sql = "SELECT * from T_ANSWER order by ANSWER_ID";
	$db->select($sql);
	if( $db->rowCount() > 0 ) {
		$old = array();
		while ($row = $db->fetch() ) {
			$out = array();
			//$out[] = $row['ANSWER_ID'];  // 省サイズのために省く
			$out[] = $old['ANSWER_DT']==$row['ANSWER_DT']?'@':date("ymd",strtotime($row['ANSWER_DT']));
			$out[] = $row['ANSWER_TYPE'];
			$out[] = $row['MEMBER_ID'];
			$out[] = $row['QUESTION_ID'];
			$out[] = $row['ANSWER_0'];
			$out[] = $row['ANSWER_1'];
			$out[] = $row['ANSWER_2'];
			$out[] = $row['ANSWER_3'];
			$out[] = $row['ANSWER_4'];
			$out[] = $row['ANSWER_5'];
			$out[] = $row['ANSWER_6'];
			$out[] = $row['ANSWER_7'];
			$out[] = $row['ANSWER_8'];
			$out[] = $row['ANSWER_9'];
			$out[] = $old['INS_DT']==$row['INS_DT']?'@':date("His",strtotime($row['INS_DT']));
			//$out[] = $out[] = $row['UPD_DT'];  // 省サイズのために省く
			//$out[] = $out[] = $row['DEL_FLG']; // 省サイズのために省く
			if( !empty($row['ANSWER_DT']) ) {
				$ans = join(',',$out);
				$ans = str_replace("0,0,0,0,0,0,0,0,0,","#9",$ans);
				$ans = str_replace("0,0,0,0,0,0,0,0,","#8",$ans);
				$ans = str_replace("0,0,0,0,0,0,0,","#7",$ans);
				$ans = str_replace("0,0,0,0,0,0,","#6",$ans);
				$ans = str_replace("0,0,0,0,0,","#5",$ans);
				$ans = str_replace("0,0,0,0,","#4",$ans);
				$ans = str_replace("0,0,0,","#3",$ans);
				$ans = str_replace("0,0,","#2",$ans);
				echo "A,",$ans . "\n";
			}
			$old = $row;
		}
	}
	return true;
}
?>
