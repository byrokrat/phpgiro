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
 * along with PhpGiro.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Hannes Forsgård <hannes.forsgard@gmail.com>
 * @copyright Copyright (c) 2011, Hannes Forsgård
 * @license http://www.gnu.org/licenses/ GNU Public License
 *
 * @package PhpGiro
 */


/**
 * Create a PhpGiro object from a file in one of the supported file formats.
 *
 * PhpGiro is a library to parse and create files using layouts common in the swedish
 * banking system. PhpGiro understands the following formats:
 *
 * PhpGiro requires PHP >= 5.3
 *
 * <h3>Supported file formats</h3>
 *
 * Layouts A - H in the legacy Autogiro Privat.
 *
 * Layouts A - C and E - J in new Autogiro (in use fall 2011). (Support for
 * layout D is currently missing, but the BgMax format can be used instead.)
 *
 * Bankgirot standard format BgMax
 *
 * PlusGirot layout N (also known as 02P).
 *
 * <h4>Supported AUTOGRIO formats</h4>
 * <pre>
 * +=============================+=====+=====+=====+
 * | LAYOUT                      | PRI | OLD | NEW |
 * +=============================+=====+=====+=====+
 * | A (Medgivandeunderlag)      |  X  |  X  |  X  |
 * +-----------------------------+-----+-----+-----+
 * | B (Betalningsunderlag)      |  X  |  X  |  X  |
 * +-----------------------------+-----+-----+-----+
 * | C (Mak./ändr. bet.underlag) |  X  |  X  |  X  |
 * +-----------------------------+-----+-----+-----+
 * | D (Betalningsspec.)         |  X  |  X  |  ?  |
 * +-----------------------------+-----+-----+-----+
 * | BGMAX (Betalningsspec.)     |  -  |  -  |  X  |
 * +-----------------------------+-----+-----+-----+
 * | E (Medgivandeavisering)     |  X  |  X  |  X  |
 * +-----------------------------+-----+-----+-----+
 * | F (Avvisade bet.)           |  X  |  X  |  X  |
 * +-----------------------------+-----+-----+-----+
 * | G (Mak./ändrings-lista)     |  X  |  X  |  X  |
 * +-----------------------------+-----+-----+-----+
 * | H (Elektr. medgivanden)     |  X  |  X  |  X  |
 * +-----------------------------+-----+-----+-----+
 * | I (Utdrag bevakningsreg)    |  -  |  X  |  X  |
 * +-----------------------------+-----+-----+-----+
 * | J (Utdrag medgivandereg)    |  -  |  X  |  X  |
 * +=============================+=====+=====+=====+
 *   PRI = autogiro privat
 *   OLD = new autogiro with old layout
 *   NEW = nya autogiro with nwe layout
 * </pre>
 *
 * @package PhpGiro
 * @version 1.0
 */
class PhpGiro_Factory {

	/**
	 * Autodiscover layout and get an Char80 object to process.
	 *
	 * <h4>Usage</h4>
	 *
	 * <code>include('GiroFactory.class.php');
	 * use PhpGiro\GiroFactory;
	 * $giroObj = GiroFactory::parse('myBankfile.txt');
	 * if ( $giroObj->hasError() ) print_r($giroObj->getErrors());
	 * while ( $section = $giroObj->getSection() ) {
	 * 	print_r($section);
	 * }</code>
	 *
	 * @param string $fname
	 * @return Char80
	 * @api
	 */
	public function parse($fname){

		$register = array(
			'PhpGiro_AG_New_D' => 'BET. SPEC & STOPP TK',
			'PhpGiro_AG_New_E' => array('AUTOGIRO', 'AG-MEDAVI'),
			'PhpGiro_AG_E' => 'AG-MEDAVI',
			'PhpGiro_AG_F' => 'FELLISTA REG.KONTRL',
			'PhpGiro_AG_New_F' => 'AVVISADE BET UPPDR',
			'PhpGiro_AG_G' => utf8_decode("MAK/ÄNDRINGSLISTA"),
			'PhpGiro_AG_New_G' => utf8_decode("MAKULERING/ÄNDRING"),
			'PhpGiro_AG_H' => "AG-EMEDGIV",
			'PhpGiro_AG_I' => "BEVAKNINGSREG",
			'PhpGiro_BG_Max' => "BGMAX"
		);

		$line = $this->getFirstLine($fname);

		foreach ( $register as $class => $ptrn ) {
			$match = true;
			if ( is_array($ptrn) ) {
				foreach ( $ptrn as $p ) {
					if ( strpos($line, $p) === false ) $match = false;
				}
			} else {
				if ( strpos($line, $ptrn) === false ) $match = false;
			}
			if ( $match ) {
				$obj = new $class();
				$obj->readFile($fname);
				$obj->parse();
				return $obj;
			}
		}

		$last =	$this->getLastLine($fname);

		if ( strpos($line, "AUTOGIRO") !== false ) {
			if ( strpos($last, '09') === 0 ) {
				$class = "PhpGiro_AG_D";
			} else {
				$class = "PhpGiro_AG_ABC";
			}
			
		} else {
			if ( strpos($last, '90') === 0 ) {
				$class = "PhpGiro_PG_N";
			} else {
				$class = "PhpGiro_AG_New_J";
				//alternativt
				//$class = "AgLayoutJ";
			}
		}

		$obj = new $class();
		$obj->readFile($fname);
		$obj->parse();
		return $obj;
	}


	/**
	 * Get first line from file, for analyzing layout.
	 * Returns false if an error occured.
	 * @param string $fname
	 * @return string
	 */
	private function getFirstLine($fname){
		$fhand = fopen($fname, "r");
		if ( $fhand === false ) return false;
		do {
			$line = fgets($fhand);
			if ( $line === false ) return false;
		} while ( preg_match("/^\s*$/", $line) ) ;
		fclose($fhand);
		return $line;
	}


	/**
	 * Get last line from file, for analyzing layout.
	 * Returns false if an error occured.
	 * @param string $fname
	 * @return string
	 */
	private function getLastLine($fname){
		$last = "";
		$fhand = fopen($fname, "r");
		if ( $fhand === false ) return false;
		while ( $line = fgets($fhand) ) {
			if ( !preg_match("/^\s*$/", $line) )  {
				$last = $line;
			}
		}
		return $last;		
		fclose($fhand);
		return $line;
	}

}

?>
