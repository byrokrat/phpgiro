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
 * Added functinality for creating A, B or C layouted files using new layout.
 * 
 * This class is not needed for parsing new A, B or C files.
 *
 * @package PhpGiro
 */
class PhpGiro_AG_New_ABC extends PhpGiro_AG_ABC {


	/* FUNCTIONS TO CREATE FILES */

	/**
	 * Write consent post for BG accout to file. When dealing with BG accounts
	 * $betNr is always the same as the account number.
	 * @param string $bgFrom BG account nr, and number to identify consent.
	 * @param bool $reject Set to TRUE if this is an answer to an online application
	 * and you DECLINE the application.
	 * @return void
	 * @api
	 */
	public function addBgConsent($bgFrom, $reject=false){
		$bgFrom = str_pad($bgFrom, 16, '0', STR_PAD_LEFT);
		$blank = str_pad("", 48);
		$reject = ($reject) ? "AV" : "  ";
		$bgTo = $this->getValue('bg');
		$this->addLine("04$bgTo$bgFrom$blank$reject");
	}


	/**
	 * Write change betNr post to file.
	 * @param string $oldBetNr Number to identify AG consent. Max 16 numbers.
	 * @param string $newBetNr Number to identify AG consent. Max 16 numbers.
	 * @return void
	 * @api
	 */
	public function changeBetNr($oldBetNr, $newBetNr){
		$oldBetNr = str_pad($oldBetNr, 16, '0', STR_PAD_LEFT);
		$newBetNr = str_pad($newBetNr, 16, '0', STR_PAD_LEFT);
		$bg = $this->getValue('bg');
		$this->addLine("05$bg$oldBetNr$bg$newBetNr");
	}

}

?>
