<?PHP
/**
 * 日付型に関するクラス（静的クラス）
 *
 * 日付の変換やフォーマットの変換をするクラス
 *
 * @access public
 * @author Atsushi Taguchi
 * @copyright Copyright (c) 2013, Tryit
 * @version 1.00
 * @since 2013/07/01
 */
/**
 */
class cDate
{
	const NO_MIN_DATE = '1980-01-01';
	const NO_MAX_DATE = '2100-01-01';

	/**
	 * 現在の時間を取得する
	 * @param string dateフォーマット文字列
	 * @return string 整形された日付文字列
	 */
	static public function now($format="") {
		return date($format==""?"Y-m-d H:i:s":$format);
	}

	/**
	 * 現在の日付を取得する
	 * @param string dateフォーマット文字列
	 * @return string 整形された日付文字列
	 */
	static public function today($format="") {
		return date($format==""?"Y-m-d":$format);
	}

	/**
	 * フォームセット用
	 * @param string  日付フォーマット
	 * @param string  日付
	 * @return string 整形された日付文字列
	 */
	static public function form($format,$date) {
		if( empty($date) ) return '';
		$ret = "";
		$src = date('Y-m-d',strtotime($date));
		if( $src > self::NO_MIN_DATE && $src < self::NO_MAX_DATE ) {
			$ret = date($format,strtotime($date));
		}
		return $ret;
	}

	/**
	 * MySqlのDATETIME値を、表示形式を指定して表示
	 * @param string datetime型の値
	 * @param string dateフォーマット文字列
	 * @return string 整形された日付文字列
	 */
	static public function datetimeView($datetime,$format) {
		if(!isset($datetime) || $datetime == "" || $datetime == "0000-00-00 00:00:00") return;
		$dates = preg_split('/[-,:;\n\/\ ]/', $datetime);
		return date($format,mktime($dates[3],$dates[4],$dates[5],$dates[1],$dates[2],$dates[0]));
	}
	/**
	 * MySqlのDATETIME値を、連想配列に格納
	 * @param string datetime型の値
	 * @return array 日付の収まったオブジェクト
	 */
	static public function datetime_array($datetime) {
		if(!isset($datetime) || $datetime == "0000-00-00 00:00:00") return;
		$dates = preg_split('/[-,:;\n\/\ ]/', $datetime);
		$ret = new stdClass;
		if( !empty($dates) ) {
			$ret->year    = $dates[0];
			$ret->month   = $dates[1];
			$ret->day     = $dates[2];
			if( !empty($dates[3]) ) $ret->hour    = $dates[3]; else $ret->hour    = '';
			if( !empty($dates[4]) ) $ret->minute  = $dates[4]; else $ret->minute  = '';
			if( !empty($dates[5]) ) $ret->second  = $dates[5]; else $ret->second  = '';
		} else {
			$ret->year    = '';
			$ret->month   = '';
			$ret->day     = '';
			$ret->hour    = '';
			$ret->minute  = '';
			$ret->second  = '';
		}
		return $ret;
	}
	/**
	 * MySqlのDATETIME値から部分を取り出す
	 * @param string datetime型の値
	 * @return array 該当数値
	 */
	static public function datetimePart($datetime,$termUnit = null) {
		$ret = self::datetime_array($datetime);
		switch($termUnit) {
			case 'year':
				return $ret->year;
			case 'month':
				return $ret->month;
			case 'day':
				return $ret->day;
			case 'hour':
				return $ret->hour;
			case 'minute':
				return $ret->minute;
			case 'second':
				return $ret->second;
			case 'gengou':
				$wareki = self::getWareki($ret->year,$ret->month,$ret->day);
				$wlist = array(
					'明治' => 1,
					'大正' => 2,
					'昭和' => 3,
					'平成' => 4,

				);
				return $wlist[$wareki->gengou];
			case 'wayear':
				$wareki = self::getWareki($ret->year,$ret->month,$ret->day);
				return $wareki->year;
		}
	}

	/**
	 * MySqlのDATETIME値を、YYYY-MM-DD HH:MM に変換
	 * @param string datetime型の値
	 * @return string 整形された日付文字列
	 */
	static public function YYYYMMDDHHMM($datetime) {
		$ret = "";
		if( !empty($datetime) ) {
			$tmp = self::datetime_array($datetime);
			$ret = $tmp->year . "-" . $tmp->month . "-" . $tmp->day  . " " . $tmp->hour . ":" . $tmp->minute;
		}
		return $ret;
	}

	/**
	 * MySqlのDATETIME値を、YYYY-MM-DD に変換
	 * @param string datetime型の値
	 * @return string 整形された日付文字列
	 */
	static public function YYYYMMDD($datetime) {
		$ret = "";
		if( !empty($datetime) ) {
			if( $datetime != '0000-00-00 00:00:00' ) {
				$tmp = self::datetime_array($datetime);
				$ret = $tmp->year . "-" . $tmp->month . "-" . $tmp->day;
			}
		}
		return $ret;
	}
	/**
	 * 日付をtimeストリーム型に変換
	 * @param integer 年
	 * @param integer 月
	 * @param integer 日
	 * @return string 整形された日付文字列
	 */
	static public function ymd2time($year,$month,$day) {
		return mktime(0, 0, 0, $month, $day, $year);
	}
	/**
	 * 日付の正確性をチェックする
	 * @param integer 年
	 * @param integer 月
	 * @param integer 日
	 * @return bool TRUE=正しい日付 FALSE=存在しない日付
	 */
	static public function ymd2check($year,$month,$day) {
		return checkdate($month, $day, $year);
	}
	/**
	 * 日付をMySQL型に変換
	 * @param integer 年
	 * @param integer 月
	 * @param integer 日
	 * @return string mysql用の日付文字列
	 */
	static public function ymd2date($year,$month,$day)
	{
		if( checkdate((int)$month, (int)$day, (int)$year) === FALSE ) {
			return "";
		}
		return date("Y-m-d",mktime(0,0,0,(int)$month,(int)$day,(int)$year));
	}
	/**
	 * 日付を日本語表記にする
	 * @param string 日付文字列
	 * @return string Y年m月d日形式の日付文字列
	 */
	static public function date2j($date)
	{
		if( empty($date) ) return "";
		$time = strtotime($date);
		$y = date("Y",$time);
		$m = date("m",$time);
		$d = date("d",$time);
		return sprintf("%4d年%2d月%2d日",$y,$m,$d);
	}
	/**
	 * 指定の文字列日付を指定のフォーマットに変換する
	 * @param string 日付文字列
	 * @param string dateフォーマット文字列
	 * @return string 整形された日付文字列
	 */
	static public function df($date,$format)
	{
		if( empty($date) ) return "";
		return date($format,strtotime($date));
	}
	/**
	 * 年月から月末日を取得
	 * @param integer 年
	 * @param integer 月
	 * @return integer 月末日の日付
	 */
	static public function getFinalDayFromDate($year,$month) {
		return date("d",mktime(0,0,0,(int)$month+1,0,(int)$year));
	}
	/**
	 * 日を月末日に合うよう丸める
	 * @param integer 年
	 * @param integer 月
	 * @param integer 日
	 * @return integer 月末日の日付
	 */
	static public function levelingFinalDayFromDate($year,$month,$day) {
		$finalDay = self::getFinalDayFromDate($year,$month);
		return ($day > $finalDay ? $finalDay:$day);
	}
	/**
	 * 年齢計算
	 * @param string 生年月日
	 * @param string 求めたい日付(未指定時は現在日付)
	 * @return integer 年齢
	 */
	static public function getAge($birth,$now = null) {
		if( empty($now) ) $now = date("Y-m-d H:i:s");
		$bVal = preg_split('/[-,:;\n\/\ ]/',$birth);
		$nVal = preg_split('/[-,:;\n\/\ ]/',$now);
		$bDate = sprintf("%04d%02d%02d",$bVal[0],$bVal[1],$bVal[2]);
		$nDate = sprintf("%04d%02d%02d",$nVal[0],$nVal[1],$nVal[2]);
		return floor(((int)($nDate) - (int)($bDate)) / 10000);
	}
	/**
	 * 現年度を得る
	 * @param integer 年（西暦）(未指定時は現在日付)
	 * @param integer 月(未指定時は現在日付)
	 * @return integer 年度
	 */
	static public function getNendo($year = null,$month = null) {
		if( empty($year) ) $yaer = date("Y");
		if( empty($month) ) $month = date("m");
		if( $month >= 4 ) {
			$nendo = $yaer;
		} else {
			$nendo = $yaer - 1;
		}
		return $nendo;
	}
	/**
	 * 和暦を得る
	 * @param integer 年
	 * @param integer 月
	 * @param integer 日
	 * @return string mysql用の日付文字列
	 */
	static public function getWareki($y, $m, $d)	{
		$wareki = new stdClass;
		//年月日を文字列として結合
		$ymd = sprintf("%04d%02d%02d", (int)$y, (int)$m, (int)$d);
		if( $ymd < '18721231' ) {
			$wareki->gengou = '';
			$wareki->year   = '';
			$wareki->month  = '';
			$wareki->day    = '';
		} else {
			if ($ymd <= "19120729") {
				$gg = "明治";
				$yy = $y - 1867;
			} elseif ($ymd >= "19120730" && $ymd <= "19261224") {
				$gg = "大正";
				$yy = $y - 1911;
			} elseif ($ymd >= "19261225" && $ymd <= "19890107") {
				$gg = "昭和";
				$yy = $y - 1925;
			} elseif ($ymd >= "19890108") {
				$gg = "平成";
				$yy = $y - 1988;
			}
			// $wareki = "{$gg}{$yy}年{$m}月{$d}日";
			$wareki->gengou = $gg;
			$wareki->year   = $yy;
			$wareki->month  = $m;
			$wareki->day    = $d;
		}
		return $wareki;
	}
	/**
	 * 年、月、日に対応した曜日を得る
	 * @param integer 年
	 * @param integer 月
	 * @param integer 日
	 * @return string 曜日
	 */
	static public function dayWeek($date) {
		$week = array("日","月","火","水","木","金","土");
		$week_no = date("w",strtotime($date));

		return $week[$week_no];
	}

	/**
	 * 日付が空かどうか確認する
	 * @param string 日付文字列
	 * @return bool true=空 false=空以外
	 */
	static public function isEmpty($datetime) {
		if( empty($datetime) ) return TRUE;
		if( $datetime == '1900-01-01 00:00:00' ) return TRUE;
		if( $datetime == '0000-00-00 00:00:00' ) return TRUE;
		if( $datetime == '0000-00-00' ) return TRUE;
		return FALSE;
	}

	/**
	 * 日付の差分を求める
	 * @param string START日時
	 * @param string End日時
	 * @return bool true=空 false=空以外
	 */
	static public function deffDate($startDate,$endDate) {
		$diffDate = new stdClass;
		$diffDate->year   = self::distance($startDate, $endDate, 'year');
		$diffDate->month  = self::distance($startDate, $endDate, 'month');
		$diffDate->week   = self::distance($startDate, $endDate, 'week');
		$diffDate->day    = self::distance($startDate, $endDate, 'day');
		return $diffDate;
	}
	/**
	 * 月数の差分を求める
	 * @param 開始日
	 * @param 終了日
	 * @return void 無し
	 */
	static public function deffMonth($sdate=NULL,$edate=NULL) {
		if( empty($sdate) ) {
			$sdate = date('Y-m-d');
		}
		if( empty($edate) ) {
			$edate = date('Y-m-d');
		}
		$sd = self::datetime_array($sdate);
		$ed = self::datetime_array($edate);

		$t1 = mktime(0,0,0,$sd->month,1,$sd->year); 
		$t2 = mktime(0,0,0,$ed->month,1,$ed->year);
//dout($t1);
//dout($t2);
		$dm = ( date("Y", $t2) - date("Y",$t1) ) *12 + ( date("n",$t2) - date("n",$t1));
		return $dm;
	}

	/**
	 * Calculation date distance between two date.
	 *
	 * @param string $dateStart  Date of start point.
	 * @param string $dateEnd  Date of end point.
	 * @param string $termUnit  Unit string of terms (ex. day, month...)
	 * @return integer  Distance between twon date depend on assigned term unit.
	 */
	static public function distance($dateStart, $dateEnd, $termUnit = null)
    {
    // Check args
		if (empty($dateStart) || empty($dateEnd)) {
			return false;
		}
	// Change to timestamp
		$timestampStart = strtotime($dateStart);
		$timestampEnd = strtotime($dateEnd);
	// Check & Compare dates
		if (empty($timestampStart) || empty($timestampEnd)) {
			return false;
		} else {
			if ($timestampStart > $timestampEnd) {
				$tmpTimestamp = $timestampStart;
				$timestampStart = $timestampEnd;
				$timestampEnd = $tmpTimestamp;
			}
		}
	// Set vars
		$timestampDistance = $timestampEnd - $timestampStart;
		$datesStart = getdate($timestampStart);
		$datesEnd = getdate($timestampEnd);
	// Calc distance
		$distance = 0;
		switch ($termUnit) {
		case 'year':
		case 'month':
			// Set vars
			$lessThanTerm = false;
			$distanceYear = $datesEnd['year'] - $datesStart['year'];
			$distanceMonth = $datesEnd['mon'] - $datesStart['mon'];
			$distanceDay = $datesEnd['mday'] - $datesStart['mday'];
			// Check day & moving down
			if ($distanceDay < 0) {
				if ($distanceMonth > 0) {
					$distanceMonth = $distanceMonth - 1;
				} else {
					if ($distanceYear > 0) {
							$distanceYear = $distanceYear - 1;
							$distanceMonth = $distanceMonth + 12 - 1;
					} else {
							$lessThanTerm = true;
					}
				}
			}
			// Check month & moving down
			if ($distanceMonth < 0) {
				if ($distanceYear > 0) {
					$distanceYear = $distanceYear - 1;
					$distanceMonth = $distanceMonth + 12;
				} else {
						$lessThanTerm = true;
				}
			}
			// Check year
			//if ($distanceYear < 0) {}
			// Check calc result & sum result.
			switch ($termUnit) {
				case 'year':
					if ($lessThanTerm) {
							$distance = 0;
					} else {
							$distance = abs($distanceYear);
					}
					break;
				case 'month':
					if ($lessThanTerm) {
							$distance += abs($distanceMonth);
					} else {
					// Sum year & month
						if (!empty($distanceYear)) {
							$distance += abs($distanceYear) * 12;
						}
						if (!empty($distanceMonth)) {
							$distance += abs($distanceMonth);
						}
					}
					break;
			}
			break;
		case 'week':
			$distance = $timestampDistance / ( (24 * 60 * 60) * 7);
			$distance = abs(floor($distance));
			break;
		case 'day':
		default:
			$distance = $timestampDistance / (24 * 60 * 60);
			$distance = abs(floor($distance));
			break;
		}
		return $distance;
	}

	/**
	 * C.P.A.標準
	 * @param integer 年
	 * @param integer 月
	 * @param integer 日
	 * @return string 曜日
	 */
	static public function njw($date) {
		$w = self::dayWeek($date);
		$str = cDate::form("n月j日",$date) . "（{$w}）";
		
		return $str;
	}
	/**
	 * C.P.A.標準
	 * @param integer 年
	 * @param integer 月
	 * @param integer 日
	 * @return string 曜日
	 */
	static public function ynjw($date) {
		$w = self::dayWeek($date);
		$str = cDate::form("Y年n月j日",$date) . "（{$w}）";
		
		return $str;
	}
}
?>
