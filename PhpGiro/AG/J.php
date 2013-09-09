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
 * AG layout J, list of consents registered with the bank.
 * @package PhpGiro
 */
class PhpGiro_AG_J extends PhpGiro_AG_Object {

	/**
	 * Status descriptions
	 * @var array $statusMsgs
	 */
	protected $statusMsgs = array(
		1 => "Godkännt för Autogiro",
		2 => "Under förfrågan",
	);


	/**
	 * Validation descriptions
	 * @var array $validMsgs
	 */
	protected $validMsgs = array(
		0 => "Medgivande OK",
		1 => "Medgivande stoppat",
	);


	/**
	 * Source descriptions
	 * @var array $sourceMsgs
	 */
	protected $sourceMsgs = array(
		1 => "Medgivande initierat av betalningsmottagare",
		2 => "Medgivande initierat av betalare via internetbank",
	);


	/* FILE STRUCTURE */


	/**
	 * Transaction code caracter length.
	 * @var int $tcLength
	 */
	protected $tcLength = 1;


	/**
	 * Layout id
	 * @var string $layout
	 */
	protected $layout = 'J';


	/**
	 * Map all transaction codes (TC) to line-parsing regexp and receiving method
	 * @var array $regexp
	 */
	protected $regexp = array('/^(\d{10})(\d{12})(\d{16})(\d)(\d)(\d{8})(.{8})(\d)(.?)(.{0,5})(\d{0,4})(\d{0,12})/', 'parseConsent');


	/**
	 * @param string $customerNr
	 * @param string $bg
	 * @see setMap()
	 */
	public function __construct($customerNr=false, $bg=false){
		parent::__construct($customerNr, $bg);
		$this->setMap();
	}


	/**
	 * Set the same regexp for 0-9, this layout does not contain TC
	 * @return void
	 */
	protected function setMap(){
		for ( $i=0; $i<10; $i++ ) {
			$this->map[$i] = $this->regexp;
		}
	}


	/* PARSING FUNCTIONS */

	/**
	 * Write section on parseing complete
	 * @return void
	 */
	protected function parsingComplete(){
		$this->writeSection();
	}

	
	/**
	 * @param string $bg
	 * @param string $orgNr
	 * @param string $betNr
	 * @param string $source
	 * @param string $activeYear
	 * @param string $created
	 * @param string $changed
	 * @param string $status1
	 * @param string $status2
	 * @param string $maxAmount
	 * @param string $clearing
	 * @param string $account
	 * @return bool TRUE on success, FALSE on failure
	 */
	protected function parseConsent($bg, $orgNr, $betNr, $source, $activeYear, $created, $changed, $status1, $status2=false, $maxAmount=false, $clearing=false, $account=false){
		self::buildStateIdNr($orgNr, $orgNrType);
		
		$c = array(
			'toBg' => ltrim($bg, '0'),
			$orgNrType => $orgNr,
			'betNr' => ltrim($betNr, '0'),
			'source' => (int)$source,
			'sourceMsg' => $this->sourceMsgs[(int)$source],
			'lastActiveYear' => $activeYear,
			'created' => $created,
			'changed' => trim($changed),
			'account' => self::buildAccountNr($betNr, $clearing, $account),
			'status' => (int)$status1,
			'statusMsg' => $this->statusMsgs[(int)$status1],
		);
		
		if ( $status2 !== false && !preg_match("/^\s*$/", $status2) ) {
			$c['valid'] = (int)$status2;
			$c['validMsg'] = $this->validMsgs[(int)$status2];
		}
		
		if ( $maxAmount && !preg_match("/^\s*$/", $maxAmount) ) {
			$c['maxAmount'] = $maxAmount;
		}
		
		$this->push($c);
		return true;
	}

}

?>
