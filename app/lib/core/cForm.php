<?PHP
/**
 * HTMLフォーム生成を支援するクラス（静的クラス）
 *
 * HTMLテンプレート上で使用するフォーム記述を簡略化するためのクラス
 *
 * @access public
 * @author Atsushi Taguchi
 * @copyright Copyright (c) 2013, Tryit
 * @version 1.00
 * @since 2013/07/01
 */
/**
 */
class cForm
{
	/**
	 * SelectのOptionフォームの展開（リスト配列に沿う版）
	 * @param string ラジオボタン名
	 * @param mixed  選択されている数値
	 * @param array  マスターデータの配列(1次元=VALUEの形になっていること)
	 * @return void  無し
	 */
	static public function deployOption($setValue,$mstList,$defValue = null) {
		$no = 1;
		if( empty($setValue) ) $setValue = $defValue;
		foreach ((array)$mstList as $mval) {
			?>
			<option value="<?= $mval ?>" <?= $setValue==$mval?'selected':'' ?>><?= $mval ?></option>
			<?
			$no++;
		}
	}
	/**
	 * SelectのOptionフォームの展開（リスト配列のキーをバリューに使う版）
	 * @param mixed  選択されている数値
	 * @param array  マスターデータの配列(1次元/[KEY]=VALUEの形になっていること)
	 * @param mixed  未選択時の値（初期値）
	 * @param mixed  未選択用の値
	 * @param array  キーリスト
	 * @return void  無し
	 */
	static public function deployOptionKey($set_value,$mstList,$def_value = "",$null_prm = "",$key_list = array()) {
		if( $null_prm != "" ) {
			?><option value=""><?= $null_prm ?></option><?
		}
		if( is_array($set_value) ) {
			// セットされた値が配列の場合
			foreach ($set_value as $skey => $sval) { // 入力値を基にselected配列を生成
				$_set_value[$sval] = "selected";
			}
			foreach ((array)$mstList as $skey => $sval) {
				if( $set_value == "" ) { // php は数字の入っていない文字列を0として認識してしまうための処置
					?><option value="<?= $skey ?>" ><?= $sval ?></option><?
				} else {
					?><option value="<?= $skey ?>" <?= $_set_value[$skey]; ?>><?= $sval ?></option><?
				}
			}
		} else {
			// セットされた値が通常変数の場合
			if( $set_value == "" ) $set_value = $def_value;
			foreach ((array)$mstList as $skey => $sval) {
				if( count($key_list) != 0 ) {
					// キーリストが指定されていた場合
					if( array_search($skey,$key_list) == FALSE ) continue;
				}
				if( $set_value == "" ) { // php は数字の入っていない文字列を0として認識してしまうための処置
					?><option value="<?= $skey ?>" ><?= $sval ?></option><?
				} else {
					?><option value="<?= $skey ?>" <?= ($set_value == $skey)?"selected":""; ?>><?= $sval ?></option><?
				}
			}
		}
	}
	/**
	 * SelectのOptionフォームの展開（数字設定版）
	 * @param mixed  選択されている数値
	 * @param integer 開始数値
	 * @param integer 終了数値
	 * @param integer 数値のステップ
	 * @param integer 未選択時の値（初期値）
	 * @param mixed  未選択用の値
	 * @return void  無し
	 */
	static public function deployOptionNumber($set_value,$start_num,$end_num,$add_num = 1,$def_value = "",$null_prm = "") {
		$_set_value = $set_value;
		$_start_num = (int)$start_num;
		$_end_num   = (int)$end_num;
		$_def_value = $def_value;
		$_add_num   = (int)$add_num;
		if( is_numeric($_def_value) == TRUE && is_numeric($_set_value) == FALSE ) {
			$_set_value = $_def_value;
		}
		if( $null_prm != "" ) {
			?><option value=""><?= $null_prm ?></option><?
		}
		if( is_numeric($_set_value) == FALSE ) $_set_value = -65535;
		if( $_start_num == $_end_num ) {
			$i = $_start_num;
			?><option value="<?= $i ?>" <?= ((int)$_set_value === $i)?"selected":""; ?>><?= $i ?></option><?
		} else if( $_start_num < $_end_num ) {
			for( $i = $_start_num ; $i <= $_end_num; $i += $_add_num ) {
				?><option value="<?= $i ?>" <?= ((int)$_set_value === $i)?"selected":""; ?>><?= $i ?></option><?
			}
		} else {
			for( $i = $_start_num ; $i >= $_end_num; $i += $_add_num ) {
				?><option value="<?= $i ?>" <?= ((int)$_set_value === $i)?"selected":""; ?>><?= $i ?></option><?
			}
		}
	}
	/**
	 * ラジオボタンフォームの展開（リスト配列を使う版）
	 * @param string ラジオボタン名
	 * @param mixed  選択されている数値
	 * @param array  マスターデータの配列(1次元=VALUEの形になっていること)
	 * @return void  無し
	 */
	static public function deployRadio($name,$setValue,$mstList,$defValue = null) {
		$no = 1;
		if( empty($setValue) ) $setValue = $defValue;
		?><!-- <?= $setValue ?> --><?
		foreach ((array)$mstList as $mval) {
			?>
			<input type="radio" name="<?= $name ?>" id="<?= $name ?>_<?= $no ?>" value="<?= $mval ?>" <?= $setValue==$mval?'checked':''; ?>>
			<label for="<?= $name ?>_<?= $no ?>"><?= $mval ?></label>
			<?
			$no++;
		}
	}

	/**
	 * ラジオボタンフォームの展開（リスト配列を使う版）
	 * @param string ラジオボタン名
	 * @param mixed  選択されている数値
	 * @param array  マスターデータの配列(1次元=VALUEの形になっていること)
	 * @return void  無し
	 */
	static public function deployRadioKey($name,$setValue,$mstList,$defValue = null) {
		$no = 1;
		if( empty($setValue) ) $setValue = $defValue;
		?><!-- <?= $setValue ?> --><?
		foreach ((array)$mstList as $mkey => $mval) {
			?>
			<input type="radio" name="<?= $name ?>" id="<?= $name ?>_<?= $no ?>" value="<?= $mkey ?>" <?= $setValue==$mkey?'checked':''; ?>>
			<label for="<?= $name ?>_<?= $no ?>"><?= $mval ?></label>
			<?
			$no++;
		}
	}

	/**
	 * チェックボックスフォームの展開（リスト配列を使う版）
	 * @param string ラジオボタン名
	 * @param mixed  選択されている数値
	 * @param array  マスターデータの配列(1次元=VALUEの形になっていること)
	 * @return void  無し
	 */
	static public function deployCheckbox($name,$setValue,$mstList) {
		$no = 1;
		foreach ((array)$mstList as $mval) {
			?>
			<input type="checkbox" name="<?= $name ?>_LIST[]" id="<?= $name ?>_<?= $no ?>" value="<?= $mval ?>" <?= strpos($setValue,'/'.$mval.'/')!==false?'checked':'' ?>>
			<label for="<?= $name ?>_<?= $no ?>"><?= $mval ?></label>
			<?
			$no++;
		}
	}

	/**
	 * チェックボックスフォームの展開（リスト配列のキーをバリューに使う版）
	 * @param string チェックボックス名
	 * @param mixed  選択されている数値
	 * @param array  マスターデータの配列(1次元/[KEY]=VALUEの形になっていること)
	 * @param string チェックボックスの区切り文字
	 * @return void  無し
	 */
	static public function deployCheckboxKey($name,$set_value,&$lists,$delimiter = "") {
		// 設定された値が配列でなかったら、分断して配列にする
		if( !is_array($set_value) ) {
			$set_value = preg_split('/[-,:;\n\/\ ]/',$set_value);
		}
		foreach ((array)$lists as $skey => $sval) {
			if( empty($set_value) ) {
				?><label class="checkbox"><input type="checkbox" name="<?= $name ?>[<?= $skey ?>]" value="<?= $skey ?>" ><?= $sval ?></label><?= $delimiter ?><?
			} else {
				?><label class="checkbox"><input type="checkbox" name="<?= $name ?>[<?= $skey ?>]" value="<?= $skey ?>" <?= in_array($skey,$set_value)?'checked':'' ?>><?= $sval ?></label><?= $delimiter ?><?
			}
		}
	}

	/**
	 * 共通Hiddenフォーム用関数
	 * @param string  hidden名
	 * @param string  値
	 * @return void  無し
	 */
	static public function hidden($name,$value) {
		if( $value != "" ) {
			?><input type="hidden" name="<?= $name ?>" value="<?= $value ?>" /><?
		}
	}

	/**
	 * requiredの書き出し
	 * @param string ラジオボタン名
	 * @param mixed  選択されている数値
	 * @param array  マスターデータの配列(1次元=VALUEの形になっていること)
	 * @return void  無し
	 */
	static public function deployRequired($requiredList) {
		foreach ((array)$requiredList as $rkey => $rval) {
			?>
			<input type="hidden" name="required[<?= $rkey ?>]" value="<?= $rval ?>">
			<?
		}
	}
}
?>
