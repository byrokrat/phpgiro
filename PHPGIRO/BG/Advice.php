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
 * Bankgiro advice class
 * @package PhpGiro
 */
class PhpGiro_BG_Advice extends PhpGiro_OCR {

	/**
	 * Get complete code for advice printing, either a type 41 advice
	 * with amount pre definied, or a type 42 with no fixed amount.
	 * If $forceAmount == true an error will be triggered if no amount
	 * is set.
	 * @param bool $forceAmount
	 * @return string
	 */
	public function getCode($forceAmount=false){
		if ( !$account = $this->getAccount() ) {
			trigger_error('Unable to make advice code, account not set.');
			return false;
		} else {
			$account = str_pad($this->account, 8, " ", STR_PAD_LEFT);
		}

		if ( !$ocr = $this->getOcr() ) {
			trigger_error('Unable to make advice code, OCR not set.');
			return false;
		} else {
			$ocr = str_pad($this->ocr, 25, " ", STR_PAD_LEFT);
		}

		if ( $amount = $this->getAmount() ) {
			$type = "41"; 
			$kr = str_pad($amount[0], 8, " ", STR_PAD_LEFT);
			$ore = $amount[1];
			$cDigit = self::getCheckDigit($kr.$ore);
		} else {
			if ( $forceAmount ) {
				trigger_error('Unable to make advice code, amount not set.');
				return false;
			}
			$type = "42";
			$kr = "        ";
			$ore = "  ";
			$cDigit = " ";
		}
		
		return "H   # $ocr #$kr $ore   $cDigit >                 $account#$type#    ";
	}


	/**
	 * Get advice image
	 * @return image resource identifier
	 */
	public function getAdvice(){
		$code = $this->getCode();

		$img = imagecreate(800, 80);
		$color = imagecolorallocate($img, 0, 0, 0);
		$bg = imagecolorallocate($img, 255, 255, 230);
		$font = "./fonts/OCRB.ttf";

		imagefill($img, 0, 0, $bg);
		imagettftext($img, 10, 0, 10, 25, $color, $font, "Det här ser inte riktigt ut som en bankgiroavi...");
		imagettftext($img, 10, 0, 10, 55, $color, $font, $code);

		return $img;
	}
}

/*
$a = new BgAdvice();

$a->setAccount('47970025');
$a->setOcr('12345682');
$a->setAmount('28798.00');

$img = $a->getAdvice();

header("Content-type: image/png");
imagepng($img);
*/

?>
