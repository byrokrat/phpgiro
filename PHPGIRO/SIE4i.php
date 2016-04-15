<?php
/**
 * This file is part of PhpGiro.
 *
 * PhpGiro is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PhpGiro is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with PhpGiro.  If not, see &lt;http://www.gnu.org/licenses/&gt;.
 *
 * @author Hannes Forsgård <hannes.forsgard@gmail.com>
 * @copyright Copyright (c) 2011, Hannes Forsgård
 * @license http://www.gnu.org/licenses/ GNU Public License
 *
 * @package PhpGiro
 */


/**
 * SIE 4I file format implementation.
 *
 * WARNING: This is not a complete implementation of the SIE file format. Only
 * the subsection SIE type 4I is supported (transactions to be imported into a
 * regular accounting software). The porpuse is to enable web
 * applications to export transaction information to accounting.
 *
 * This implementation is based on specification 4B from the maintainer (SIE
 * gruppen) dated 2008-09-30.
 *
 * @package PhpGiro
 */
class PhpGiro_SIE4i {

	/**
	 * New line sequence used
	 * @var string NEW_LINE
	 */
	const NEW_LINE = "\r\n";

	/**
	 * Posts in this SIE
	 * @var array $posts
	 */
	private $posts;

	/**
	 * Checksum for current state
	 * @see checksum()
	 * @var bool|int $checksum
	 */
	private $checksum = false;

	/**
	 * @param string $fnamn
	 * @param string $creator
	 * @param string $appName
	 * @param string $appVersion
	 * @param bool $setKpTyp
	 * @param bool $setRar If TRUE rar will be set to current year
	 */
	public function __construct($fnamn="default", $creator="PhpGiro", $appName="PhpGiro", $appVersion='1', $setKpTyp=true, $setRar=false){
		$this->setPost('PROGRAM', array($appName, $appVersion));
		$this->setPost('FORMAT', 'PC8');
		$this->setPost('GEN', array(date('Ymd'), $creator));
		$this->setPost('SIETYP', 4);
		$this->setFnamn($fnamn);
		if ( $setKpTyp ) $this->setPost('KPTYP', 'EUBAS97');
		if ( $setRar ) $this->setRar();
	}


	/* ---------------------------------------------------------------------------------------- */


	/**
	 * Overwrite an existing post. Or create a new one if post does not exist.
	 * @param string $name
	 * @param mixed $val
	 * @param array $subposts
	 * @return TRUE on success, FALSE on failure
	 */
	public function replacePost($name, $val, $subposts=false){
		foreach ( $this->posts as &$post ) {
			if ( $post[0] == $name ) {
				$post = array($name, $val, $subposts);
				return true;
			}
		}
		$this->setPost($name, $val, $subposts);
	}

	/**
	 * Set a new post
	 * @param string $name
	 * @param mixed $val
	 * @param array $subposts
	 * @return TRUE on success, FALSE on failure
	 */
	public function setPost($name, $val, $subposts=false){
		$this->posts[] = array($name, $val, $subposts);
		return true;
	}


	/* ---------------------------------------------------------------------------------------- */


	/**
	 * Set buisiness name
	 * @param string $name
	 * @return TRUE on success, FALSE on failure
	 */
	public function setFnamn($name){
		return $this->replacePost('FNAMN', $name);
	}

	/**
	 * Set accounting year
	 * @param int $yearcount 0 for this year, -1 for previous year
	 * @param string $start
	 * @param string $stop
	 * @return TRUE on success, FALSE on failure
	 */
	public function setRar($yearcount=0, $start=false, $stop=false){
		if ( !$start ) $start = date('Y')."0101";
		if ( !$stop ) $stop = date('Y')."1231";
		return $this->setPost('RAR', array($yearcount, $start, $stop));
	}


	/**
	 * Set account
	 * @param int $nr
	 * @param string $title
	 * @return TRUE on success, FALSE on failure
	 */
	public function setKonto($nr, $title){
		return $this->setPost('KONTO', array($nr, $title));
	}

	/**
	 * Set a verificatin post
	 * @param string $date (In the form YYYYMMDD)
	 * @param string $text
	 * @param array $trans transactions in the form $account => $sum
	 * @return TRUE on success, FALSE on failure (including of transactions
	 * are not in balance).
	 */
	public function setVer($date, $text, $trans){
		$balans = 0;
		$transposts = array();
		foreach ( $trans as $konto => $sum ) {
			$transposts[] = array('TRANS', array($konto, '{}', $sum));
			$balans += $sum;
		}
		if ( $balans !== 0 ) return false;
		return $this->setPost('VER', array("", "", $date, $text), $transposts);
	}


	/* ---------------------------------------------------------------------------------------- */


	/**
	 * Convert to string. The string represents a valid SIE file.
	 * @return string
	 */
	public function __tostring(){
		$posts = self::iconv_array("UTF-8", "CP437", $this->posts);
		$str = "#FLAGGA 0".self::NEW_LINE;
		if ( $this->checksum !== false ) $str .= "#KSUMMA".self::NEW_LINE;
		foreach ( $posts as $post ) $str .= self::postToStr($post);
		if ( $this->checksum !== false ) $str .= "#KSUMMA $this->checksum".self::NEW_LINE;
		return $str;
	}

	/**
	 * Get current SIE as string
	 * @return string
	 */
	public function getSie(){
		return (string)$this;
	}

	/**
	 * Write to file.
	 * @param string $file to write to
	 */
	public function tofile($file){
		return file_put_contents($file, (string)$this);
	}


	/* ---------------------------------------------------------------------------------------- */


	/**
	 * EXPERIMENTAL, need test values to complete. Calculate checksum for
	 * current state. Do this just before output, or your checksum will be invalid.
	 * @return TRUE on success, FALSE on failure
	 */
	public function checksum(){
		trigger_error("SIE4i::checksum() is experimental and should not be used for production purposes.", E_USER_NOTICE);
		
		$toHash = "";
		$posts = self::iconv_array("UTF-8", "CP437", $this->posts);
		foreach ( $posts as $post ) {
			$toHash .= "#$post[0]";
			if ( is_array($post[1]) ) $post[1] = implode('', $post[1]);
			$toHash .= $post[1];
			if ( is_array($post[2]) ) {
				foreach ( $post[2] as $subpost ) {
					$toHash .= "#$subpost[0]";
					if ( is_array($subpost[1]) ) $subpost[1] = implode('', $subpost[1]);
					$toHash .= $subpost[1];
				}
			}
		}
		$toHash = preg_replace('/\{|\}/', '', $toHash);
		$this->checksum = sprintf("%u", crc32($toHash));
		return true;
	}

	
	/* ---------------------------------------------------------------------------------------- */


	/**
	 * Convert a post array to string. $post[0] is treated as post name
	 * $post[1] as post fields and $post[2] as subposts.
	 * @param array $post
	 * @return str
	 */
	private static function postToStr($post){
		$str = "#$post[0] ";
		$post[1] = self::quote_array($post[1]);
		if ( is_array($post[1]) ) $post[1]= implode(' ', $post[1]);
		$str .= "$post[1]".self::NEW_LINE;
		if ( is_array($post[2]) ) {
			$post[2] = self::quote_array($post[2]);
			//subposts
			$str .= "{".self::NEW_LINE;
			foreach ( $post[2] as $subpost ) $str.= self::postToStr($subpost);
			$str .= "}".self::NEW_LINE;
		}
		return $str;
	}

	/**
	 * Set quotes around strings that contain whitespaces
	 * @param array|string $val
	 * @return array|string
	 */
	private static function quote_array($val){
		if ( is_string($val) ) {
			$val = trim($val);
			$val = addslashes($val);
			if ( preg_match('/(\s)|(^\s*$)/', $val) ) {
				$val = '"'.$val.'"';
			}
		} elseif ( is_array($val) ) {
			foreach ( $val as &$sub ){
				$sub = self::quote_array($sub);
			}
		}
		return $val;
	}

	/**
	 * Get array encoded as CP48 (CP437).
	 * @param string $in_charset
	 * @param string $out_charset 
	 * @param array $arr
	 * @return array
	 */
	private static function iconv_array($in_charset, $out_charset, $arr){
		foreach ( $arr as $key => &$val ) {
			if ( is_string($val) ) {
				$val = iconv($in_charset, $out_charset, $val);
			} elseif ( is_array($val) ) {
				$val = self::iconv_array($in_charset, $out_charset, $val);
			}
		}
		return $arr;
	}

}

/*

Example code:

ini_set('display_errors', 1);
header("Content-Type: text/plain");

$s = new SIE4i('SAC', 'kassören', 'Organiseraren', "1.0.5");

//rar is optinal
$s->setRar();

//måste alla konton vara satta??
$s->setKonto(1910, "Kassa");
$s->setKonto(3540, "Försäljning");

$s->setVer("20110501", "Testverifikation åäö", array(1910=>1000, 3540=>-1000));

//$s->checksum();

echo $s->getSie();

$s->tofile('../../sac/var/testverifikation.si');

*/

?>
