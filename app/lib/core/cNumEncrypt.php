<?PHP
/**
 * 数値の簡易暗号化クラス（静的クラス）
 *
 * 数値を認識されずらい形で復号可能な暗号化をします
 *
 * <pre>
 * *********************************************************<br>
 * 　使い方<br>
 * *********************************************************<br>
 * ■暗号化<br>
 * $enc = cEncrypt::num2code($num);<br>
 * ■復号化<br>
 * $num = cEncrypt::code2num($enc);<br>
 * </pre>
 *
 * @access public
 * @author Atsushi Taguchi
 * @copyright Copyright (c) 2013, Tryit
 * @version 1.00
 * @since 2013/07/01
 */
/**
 */
class cNumEncrypt
{
	/**
	 * 数値を暗号コードにする
	 * @param integer 数値
	 * @return string 暗号化文字列
	 */
	static public function num2code($num) {
		static $num_char1  = array( 0=>S,1=>G,2=>t,3=>H,4=>Z,5=>m,6=>w,7=>M,8=>p,9=>k,10=>X,11=>F,12=>O,13=>u,14=>r,15=>P,16=>E,17=>z,18=>n,19=>q,20=>R,21=>T,22=>U,23=>Q,24=>W,25=>J,26=>h,27=>I,28=>i,29=>y,30=>d,31=>K,32=>B,33=>L,34=>N );
		static $dmy_char1  = array( 0=>c,1=>x,2=>".",3=>C,4=>Y,5=>a,6=>l,7=>g,8=>b,9=>D,10=>e,11=>j,12=>f,13=>s,14=>V,15=>A,16=>o );
		static $num_char2  = array( 0=>U,1=>o,2=>y,3=>c,4=>r,5=>f,6=>H,7=>m,8=>k,9=>C,10=>W,11=>S,12=>".",13=>V,14=>B,15=>p,16=>t,17=>Q,18=>G,19=>O,20=>N,21=>T,22=>R,23=>Y,24=>J,25=>b,26=>i,27=>E,28=>K,29=>L,30=>n,31=>D,32=>q,33=>s,34=>z );
		static $dmy_char2  = array( 0=>d,1=>x,2=>u,3=>w,4=>X,5=>g,6=>A,7=>P,8=>Z,9=>a,10=>h,11=>I,12=>M,13=>j,14=>F,15=>e,16=>l );
		$res1 = self::numEncord($num,$num_char1,$dmy_char1);
		$res2 = self::numEncord($num,$num_char2,$dmy_char2);
		return $res1.$res2;
	}
	/**
	 * 暗号コードを数値にする
	 * @param string 暗号化文字列
	 * @return integer 数値
	 */
	static public function code2num($code) {
		static $code_char1 = array( S=>0,G=>1,t=>2,H=>3,Z=>4,m=>5,w=>6,M=>7,p=>8,k=>9,X=>10,F=>11,O=>12,u=>13,r=>14,P=>15,E=>16,z=>17,n=>18,q=>19,R=>20,T=>21,U=>22,Q=>23,W=>24,J=>25,h=>26,I=>27,i=>28,y=>29,d=>30,K=>31,B=>32,L=>33,N=>34 );
		static $code_char2 = array( U=>0,o=>1,y=>2,c=>3,r=>4,f=>5,H=>6,m=>7,k=>8,C=>9,W=>10,S=>11,"."=>12,V=>13,B=>14,p=>15,t=>16,Q=>17,G=>18,O=>19,N=>20,T=>21,R=>22,Y=>23,J=>24,b=>25,i=>26,E=>27,K=>28,L=>29,n=>30,D=>31,q=>32,s=>33,z=>34 );
		if( is_numeric($code) == TRUE ) return -1;
		$res1 = self::codeDecord(substr($code, 0, 8),$code_char1);
		$res2 = self::codeDecord(substr($code, 8, 8),$code_char2);
		if( $res1 == $res2 ) return $res1;
		else return -1;
	}
	/**
	 * 数値を８桁に暗号エンコード
	 * @param integer 数値
	 * @param array 暗号化に使用するキャラクター配列パターン１
	 * @param array 暗号化に使用するキャラクター配列パターン２
	 * @return integer 数値
	 */
	static private function numEncord($num,&$astr,&$dstr)
	{
		$str = "";
		while(-1)
		{
			$numy = $num % count($astr);
			$str .= $astr[$numy];
			$num = (int)($num / count($astr));
			if( $num == 0 ) break;
		}
		$strcnt = 8-strlen($str);
		$strcnt1 = rand()%$strcnt;
		for( $i=0; $i < $strcnt1; $i++ )
		{
			$str = $dstr[rand()%count($dstr)].$str;
		}
		$strcnt2 = $strcnt-$strcnt1;
		for( $i=0; $i < $strcnt2; $i++ )
		{
			$str = $str.$dstr[rand()%count($dstr)];
		}
		return $str;
	}
	/**
	 * 暗号を数値８桁にデコード
	 * @param integer 暗号コード
	 * @param array 復号化に使用するキャラクター配列パターン
	 * @return integer 数値
	 */
	/*
	static private codeDecord($code,&$astr) {
		if( strlen($code) != 8 ) { return -1 };
		$num = 0;
		$ofs = 0;
		for( $i=0; $i < 8; $i++ )
		{
			$char = substr($code,$i,1);
			if( $char == '' ) break;
			if( isset($astr[$char]) )
			{
				$num += $astr[$char]  *  pow(count($astr), $ofs);
				$ofs++;
			}
		}
		return $num;
	}
	*/
}
?>