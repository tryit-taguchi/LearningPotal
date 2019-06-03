<?php
/**
 * 正規化クラス（静的クラス）
 *
 * Webフォーム等から引き渡された値を正規化する
 *
 * @access public
 * @author Atsushi Taguchi
 * @copyright Copyright (c) 2013, Tryit
 * @version 1.00
 * @since 2013/07/01
 */
/**
 */
class cNormalize
{
	/**
	 * コンストラクタ
	 */
	function __construct() {
	}
	/**
	 * 全角から半角化
	 * @param string 変換元文字列
	 * @return string 変換後文字列
	 */
	static public function zen2Han($str) {
		$str = mb_convert_kana($str, "a");
		$str = self::trim($str);
		return $str;
	}
	/**
	 * 全角から半角化して大文字化
	 * @param string 変換元文字列
	 * @return string 変換後文字列
	 */
	static public function zen2HanUpper($str) {
		$str = mb_convert_kana($str, "a");
		$str = self::trim($str);
		$str = strtoupper($str);
		return $str;
	}
	/**
	 * 半角から全角化
	 * @param string 変換元文字列
	 * @return string 変換後文字列
	 */
	static public function han2Zen($str) {
		$str = mb_convert_kana($str, "AKV");
		$str = self::trim($str);
		return $str;
	}
	/**
	 * ひらがなからカタカナ化
	 * @param string 変換元文字列
	 * @return string 変換後文字列
	 */
	static public function hira2kata($str) {
		$str = mb_convert_kana($str, "C");
		$str = self::trim($str);
		return $str;
	}
	/**
	 * 半角カタカナを全角化
	 * @param string 変換元文字列
	 * @return string 変換後文字列
	 */
	static public function hanKana2ZenKana($str) {
		$str = mb_convert_kana($str, "KV");
		$str = self::trim($str);
		return $str;
	}
	/**
	 * フリガナをひらがなに統一
	 * @param string 変換元文字列
	 * @return string 変換後文字列
	 */
	static public function hanKana2ZenHira($str) {
		$str = mb_convert_kana($str, "HVc");
		$str = self::trim($str);
		return $str;
	}
	/**
	 * ふりがなをカタカナに統一
	 * @param string 変換元文字列
	 * @return string 変換後文字列
	 */
	static public function hanKana2ZenKata($str) {
		$str = self::trim($str);
		$str = mb_convert_kana($str, "SAKVC");
		return $str;
	}

	/**
	 * ローマ字ネーム　一文字目を大文字後を小文字
	 * @param string 変換元文字列
	 * @return string 変換後文字列
	 */
	static public function nameRoma($str) {
		$str = self::trim($str);
		$str = self::zen2Han($str);
		$str = lcfirst(strtr(ucwords(strtr($str, ['_' => ' '])), [' ' => ' ']));
		$str = ucfirst($str);
		return $str;
	}
	
	/**
	 * ローマ字ネーム　一文字目を大文字後を小文字
	 * @param string 変換元文字列
	 * @return string 変換後文字列
	 */
	public static function underscore($str) {
		return ltrim(strtolower(preg_replace('/[A-Z]/', '_\0', $str)), '_');
	}

	/**
	 * キャメルケース変換
	 * @param string 変換元文字列
	 * @return string 変換後文字列
	 */
	public static function camelize($str) {
		return lcfirst(strtr(ucwords(strtr($str, ['_' => ' '])), [' ' => '']));
	}

	/**
	 * 郵便番号のノーマライズ
	 * @param string 変換元文字列
	 * @return string 変換後文字列
	 */
	static public function zip($str) {
		$str = self::zen2Han($str);
		$str = str_replace("－","-",$str); // 全ハイフンを半ハイフンに変換
		$str = str_replace("ー","-",$str); // 長音を半ハイフンに変換
		return $str;
	}
	/**
	 * 郵便番号のノーマライズ(ハイフンつき)
	 * @param string 変換元文字列
	 * @return string 変換後文字列
	 */
	static public function zipHyphen($str) {
		$str = self::zen2Han($str);
		$str = cUtility::getNumberStr($str); // 数字のみを取り出す
		return substr($str, 0, 3) . "-" . substr($str, 3, 4);
	}
	/**
	 * メールアドレスのノーマライズ
	 * @param string 変換元文字列
	 * @return string 変換後文字列
	 */
	static public function eMail($str) {
		$str = self::zen2Han($str);
		$str = str_replace(",",".",$str); // カンマをピリオドに変換
		$str = self::trim($str);
		return $str;
	}
	/**
	 * 複数行テキストのノーマライズ
	 * @param string 変換元文字列
	 * @return string 変換後文字列
	 */
	static public function multiLineText($str) {
		$str = mb_convert_kana($str, "KV");
		$str = self::trim($str);
		return $str;
	}
	/**
	 * 共通トリム
	 * @param string 変換元文字列
	 * @param bool   スペースの連続は一つにまとめる場合はTRUE
	 * @param string 変換元文字列
	 * @return string 変換後文字列
	 */
	static public function trim($str,$sflg = FALSE) {
		$str = str_replace("　"," ",$str);      // 全角スペースは半角スペースに統一する
		if( $sflg == TRUE )
			$str = preg_replace("/\s+/", " ",$str); // スペースの連続は一つにまとめる
		$str = trim($str);                      // トリミングする
		return $str;
	}
	/**
	 * 浮動小数点型数値のノーマライズ
	 * @param string 変換元文字列
	 * @return string 変換後文字列
	 */
	static public function float($str) {
		if( strlen($str) > 0 ) {
			$str = self::zen2Han($str);
			preg_match_all("/[0-9.]+/",$str,$match);
			$tmp = "";
			foreach($match[0] as $val) {
				$tmp .= $val;
			}
			if( strlen($tmp) > 0 ) {
				$str = (float)$tmp;
			} else {
				$str = "";
			}
		}
		return (string)$str;
	}

	/**
	 * 数値が入力されていた場合、数値以外は0にする
	 * @param string 変換元文字列
	 * @return string 変換後文字列
	 */
	static public function number($str) {
		if( strlen($str) > 0 ) {
			$str = self::zen2Han($str);
			preg_match_all("/[0-9]+/",$str,$match);
			$tmp = "";
			foreach($match[0] as $val) {
				$tmp .= $val;
			}
			if( strlen($tmp) > 0 ) {
				$str = (int)$tmp;
			} else {
				$str = "";
			}
		}
		return (string)$str;
	}

	/**
	 * 数値が入力されていた場合、数値以外は0にする
	 * @param string 変換元文字列
	 * @return string 変換後文字列
	 */
	static public function tel($str) {
		if( strlen($str) > 0 ) {
			$str = self::zen2Han($str);
			preg_match_all("/[0-9]+/",$str,$match);
			$tmp = "";
			foreach($match[0] as $val) {
				$tmp .= $val;
			}
			if( strlen($tmp) > 0 ) {
				$str = $tmp;
			} else {
				$str = "";
			}
		}
		return (string)$str;
	}

	/**
	 * 数値を漢字混じり文字列に変換する
	 * @param	double $n 数値
	 * @return	string 変換後文字列
	*/
	static public function int2kanji($n) {
		$tbl = array(1=>'万', 2=>'億', 3=>'兆', 4=>'京');

		preg_match("/([0-9]+)(\.[0-9]*)(E\+[0-9]*)/", $n, $matches);
		if (!isset($matches[3]) && isset($matches[2]))	return $n;	//小数はそのまま返す

		$m = preg_match("/([0-9]*)(E\+[0-9]*)/", $n) ? strval2($n) : $n;
		$s = "";
		for ($i = count($tbl); $i >= 1; $i--) {
			$b = bcpow(10, 4 * $i);
			$a = bcdiv($m, $b, 0);
			if ($a > 0) {
				$s .= ($a . $tbl[$i]);
				$m = bcmod($m, $b);
			}
		}
		if ($m > 0)		$s .= $m;

		return $s;
	}

	/**
	 * 漢数字を数値に変換する
	 * @param	string $kanji 漢数字
	 * @param	int $mode 出力書式／1=3桁カンマ区切り，2=漢字混じり, それ以外=ベタ打ち
	 * @return	double 数値
	*/
	static public function kan2num($kanji, $mode=3) {
		if( empty($kanji) ) return $kanji;
		if( is_numeric($kanji) ) return $kanji;
		//全角＝半角対応表
		$kan_num = array(
			'０' => 0, '〇' => 0,
			'１' => 1, '一' => 1, '壱' => 1,
			'２' => 2, '二' => 2, '弐' => 2,
			'３' => 3, '三' => 3, '参' => 3,
			'４' => 4, '四' => 4,
			'５' => 5, '五' => 5,
			'６' => 6, '六' => 6,
			'７' => 7, '七' => 7,
			'８' => 8, '八' => 8,
			'９' => 9, '九' => 9
		);
		//位取り
		$kan_deci_sub = array('十' => 10, '百' => 100, '千' => 1000);
		$kan_deci = array('万' => 10000, '億' => 100000000, '兆' => 1000000000000, '京' => 10000000000000000);

		//右側から解釈していく
		$ll = mb_strlen($kanji);
		$a = '';
		$deci = 1;
		$deci_sub = 1;
		$m = 0;
		$n = 0;
		for ($pos = $ll - 1; $pos >= 0; $pos--) {
			$c = mb_substr($kanji, $pos, 1);
			if (isset($kan_num[$c])) {
				$a = $kan_num[$c] . $a;
			} else if (isset($kan_deci_sub[$c])) {
				if ($a != '')	$m = $m + $a * $deci_sub;
				else if ($deci_sub != 1)	$m = $m + $deci_sub;
				$a = '';
				$deci_sub = $kan_deci_sub[$c];
			} else if (isset($kan_deci[$c])) {
				if ($a != '')	$m = $m + $a * $deci_sub;
				else if ($deci_sub != 1)	$m = $m + $deci_sub;
				$n = $m * $deci + $n;
				$m = 0;
				$a = '';
				$deci_sub = 1;
				$deci = $kan_deci[$c];
			}
		}

		$ss = '';
		if (preg_match("/^(0+)/", $a, $regs) != FALSE)	$ss = $regs[1];
		if ($a != '')	$m = $m + $a * $deci_sub;
		else if ($deci_sub != 1)	$m = $m + $deci_sub;
		$n = $m * $deci + $n;

		//出力書式に変換
		if ($ss == '') {
			$dest = $n;
			switch ($mode) {
				case 1:
					$dest = number_format($n);
					break;
				case 2:
					$dest = self::int2kanji($n);
					break;
				default:
			}
		} else if ($n == 0) {
			$dest = $ss;
		} else {
			$dest = $ss . $n;
		}

		return $dest;
	}

	/**
	 * ハイフン無し電話番号をハイフンありにする
	 * @param	string $tel ハイフン無し電話番号
	 * @return	double $tel ハイフン有り電話番号
	*/
	/*
dout(cNormalize::telHyphen("0312345678"));
dout(cNormalize::telHyphen("0482345678"));
dout(cNormalize::telHyphen("09012345678"));
dout(cNormalize::telHyphen("02012345678"));
	*/
	static public function telHyphen($intel) {
		$tel = cUtility::getNumberStr($intel); // 数字のみを取り出す

		if( empty($tel) ) return "";  // 空の場合は抜ける

		$kuni = substr($tel,0,1);
		// 国際番号の場合
		if( $kuni != '0' ) {
			$kuninum = "";
			$otel = $tel;
			$kuni3 = array('998','996','995','994','993','992','977','976','975','974','973','972','971','968','967','966','965','964','963','962','961','960','886','880','856','855','853','852','850','692','691','690','689','688','687','686','685','683','682','680','679','678','677','676','675','674','673','672','670','599','598','597','596','595','594','593','592','591','590','509','508','507','506','505','504','503','502','501','500','423','421','420','389','387','386','385','382','381','380','378','377','376','375','374','373','372','371','370','359','358','357','356','355','354','353','352','351','350','299','298','297','291','290','269','268','267','266','265','264','263','262','261','260','258','257','256','255','254','253','252','251','250','249','247','245','244','243','242','241','240','239','238','237','236','235','234','233','232','231','230','229','228','227','226','225','224','223','222','221','220','218','216','213','212');
			$kuni  = substr($tel,0,3);
			if( in_array($kuni,$kuni3) ) {
				$kuninum = substr($tel,0,3);
				$otel    = substr($tel,3,12);
			} else {
				$kuni2  = array('98','95','94','93','92','91','90','86','84','82','81','66','65','64','63','62','61','60','58','57','56','55','54','53','52','51','49','48','47','46','45','44','43','41','40','39','36','34','33','32','31','30','27','20');
				$kuni  = substr($tel,0,2);
				if( in_array($kuni,$kuni2) ) {
					$kuninum = substr($tel,0,2);
					$otel    = substr($tel,2,12);
				} else {
					$kuni1  = array('1','7');
					$kuni  = substr($tel,0,1);
					if( in_array($kuni,$kuni1) ) {
						$kuninum = substr($tel,0,1);
						$otel    = substr($tel,1,12);
					}
				}
			}
			if( !empty($kuninum) ) {
				return $kuninum . '-' . substr($otel,0,3) . '-' . substr($otel,3,3) . '-' . substr($otel,6,5);
				/*
				if( substr($otel,0,1) == 7
				 || substr($otel,0,1) == 8
				 || substr($otel,0,1) == 9 ) {
					return $kuninum . '-' . substr($otel,0,2) . '-' . substr($otel,2,4) . '-' . substr($otel,6,4);
				} else {
					return $kuninum . '-' . substr($otel,0,3) . '-' . substr($otel,3,3) . '-' . substr($otel,6,5);
				}
				*/
			} else {
				return $intel;
			}
		}
		// 携帯番号・ＩＰ電話等
		$shigai = substr($tel,0,3);
		$shigai3 = array('090','080','070','050','020');
		if( in_array($shigai,$shigai3) ) {
			return substr($tel,0,3) . '-' . substr($tel,3,4) . '-' . substr($tel,7,4);
		}
		// 市外局番5桁
		$shigai5 = array('01267','01372','01374','01377','01392','01397','01398','01456','01457','01466','01547','01558','01564','01586','01587','01632','01634','01635','01648','01654','01655','01656','01658','04992','04994','04996','04998','05769','05979','07468','08387','08388','08396','08477','08512','08514','09496','09802','09912','09913','09969');
		$shigai = substr($tel,0,5);
		if( in_array($shigai,$shigai5) ) {
			return substr($tel,0,5) . '-' . substr($tel,5,1) . '-' . substr($tel,6,4);
		}
		// 市外局番4桁（フリーダイヤル）
		$shigai4 = array('0120','0800','0070','0077','0088');
		$shigai = substr($tel,0,4);
		if( in_array($shigai,$shigai4) ) {
			return substr($tel,0,4) . '-' . substr($tel,4,3) . '-' . substr($tel,7,3);
		}
		// 市外局番4桁
		$shigai4 = array('0123','0124','0125','0126','0133','0134','0135','0136','0137','0138','0139','0142','0143','0144','0145','0146','0152','0153','0154','0155','0156','0157','0158','0162','0163','0164','0165','0166','0167','0172','0173','0174','0175','0176','0178','0179','0182','0183','0184','0185','0186','0187','0191','0192','0193','0194','0195','0197','0198','0220','0223','0224','0225','0226','0228','0229','0233','0234','0235','0237','0238','0240','0241','0242','0243','0244','0246','0247','0248','0250','0254','0255','0256','0257','0258','0259','0260','0261','0263','0264','0265','0266','0267','0268','0269','0270','0274','0276','0277','0278','0279','0280','0282','0283','0284','0285','0287','0288','0289','0291','0293','0294','0295','0296','0297','0299','0422','0428','0436','0438','0439','0460','0463','0465','0466','0467','0470','0475','0476','0478','0479','0480','0493','0494','0495','0531','0532','0533','0536','0537','0538','0539','0544','0545','0547','0548','0550','0551','0553','0554','0555','0556','0557','0558','0561','0562','0563','0564','0565','0566','0567','0568','0569','0572','0573','0574','0575','0576','0577','0578','0581','0584','0585','0586','0587','0594','0595','0596','0597','0598','0599','0721','0725','0735','0736','0737','0738','0739','0740','0742','0743','0744','0745','0746','0747','0748','0749','0761','0763','0765','0766','0767','0768','0770','0771','0772','0773','0774','0776','0778','0779','0790','0791','0794','0795','0796','0797','0798','0799','0820','0823','0824','0826','0827','0829','0833','0834','0835','0836','0837','0838','0845','0846','0847','0848','0852','0853','0854','0855','0856','0857','0858','0859','0863','0865','0866','0867','0868','0869','0875','0877','0879','0880','0883','0884','0885','0887','0889','0892','0893','0894','0895','0896','0897','0898','0920','0930','0940','0942','0943','0944','0946','0947','0948','0949','0950','0952','0954','0955','0956','0957','0959','0964','0965','0966','0967','0968','0969','0972','0973','0974','0977','0978','0979','0980','0982','0983','0984','0985','0986','0987','0993','0994','0995','0996','0997');
		$shigai = substr($tel,0,4);
		if( in_array($shigai,$shigai4) ) {
			return substr($tel,0,4) . '-' . substr($tel,4,2) . '-' . substr($tel,6,4);
		}
		// 市外局番3桁
		$shigai3 = array('011','015','017','018','019','022','023','024','025','026','027','028','029','042','043','044','045','046','047','048','049','052','053','054','055','058','059','072','073','075','076','077','078','079','082','083','084','086','087','088','089','092','093','095','096','097','098','099');
		$shigai = substr($tel,0,3);
		if( in_array($shigai,$shigai3) ) {
			return substr($tel,0,3) . '-' . substr($tel,3,3) . '-' . substr($tel,6,4);
		}
		// 市外局番3桁
		$shigai2 = array('03','04','06');
		$shigai = substr($tel,0,2);
		if( in_array($shigai,$shigai2) ) {
			return substr($tel,0,2) . '-' . substr($tel,2,4) . '-' . substr($tel,6,4);
		}
		// 該当しない場合は不明なので、そのままにする
		return $intel;
	}

	/**
	 * 電話番号を固定・携帯正しい方に選別する
	 * @param array セットするデータ
	 * @return bool TRUE=成功 FALSE=失敗
	 */
	static public function selectTel($intel,$inmov) {
		$tmptel = $intel;
		$tmpmov = $inmov;

		$telh    = substr($intel,0,3);
		$movh    = substr($inmov,0,3);
		$shigai3 = array('090','080','070','020');
		if( !empty($intel) ) {
			if( in_array($telh,$shigai3) ) {
				$retmov = $intel;
			} else {
				$rettel = $intel;
			}
		}
		if( !empty($inmov) ) {
			if( in_array($movh,$shigai3) ) {
				$retmov = $inmov;
			} else {
				$rettel = $inmov;
			}
		}
		return array($rettel,$retmov);
	}
}
?>