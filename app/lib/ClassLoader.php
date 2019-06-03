<?PHP
/**
 * Classが定義されていない場合に、ファイルを探すクラス
 *
 * 
 *
 * @access public
 * @author Atsushi Taguchi
 * @copyright Copyright (c) 2017, Tryit
 * @version 1.00
 * @since 2017/05/02
 */
/**
 */
class ClassLoader
{
	// class ファイルがあるディレクトリのリスト
	private static $dirs;

	/**
	 * クラスが見つからなかった場合呼び出されるメソッド
	 * spl_autoload_register でこのメソッドを登録してください
	 * @param  string $class 名前空間など含んだクラス名
	 * @return bool 成功すればtrue
	 */
	public static function loadClass($class)
	{
		foreach (self::directories() as $directory) {
			// 名前空間や疑似名前空間をここでパースして
			// 適切なファイルパスにしてください
			$file_name = "{$directory}/{$class}.php";

			if (is_file($file_name)) {
				require $file_name;

				return true;
			}
		}
	}

	/**
	 * ディレクトリリスト
	 * @return array フルパスのリスト
	 */
	private static function directories()
	{
		if (empty(self::$dirs)) {
			$base = __DIR__;
			self::$dirs = array(
				// ここに読み込んでほしいディレクトリを足していきます
				$base . '/core',
				$base . '/dba',
				$base . '/common',
				$base . '/PHPExcel'
			);
		}
		return self::$dirs;
	}
}
spl_autoload_register(array('ClassLoader', 'loadClass'));
?>
