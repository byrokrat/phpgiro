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
 * @package PhpGiro
 */
class PhpGiro_PG_N extends PhpGiro_Char80 {

	/**
	 * Layout names
	 * @var array $layoutNames
	 */
	protected $layoutNames = array(
		'N' => "INBETALNINGSSERVICE - Sammanställning av Inkommande betalningar",
	);

	/**
	 * Nr of transaction posts in file
	 * @var int $postCount
	 */
	private $postCount = 0;

	/**
	 * File transaction post sum
	 * @var float $postSum
	 */
	private $postSum = 0;



	/* FILE STRUCTURE */


	/**
	 * Layout id
	 * @var string $layout
	 */
	protected $layout = 'N';


	/**
	 * Regex represention a valid file structure
	 * @var string $struct
	 */
	protected $struct = "/^(0010(2030(40)+50)+90)+$/";


	/**
	 * Map transaction codes (TC) to line-parsing regexp and receiving method
	 * @var array $map
	 */
	protected $map = array(
		'00' => array("/^00(\d{6})(.{33})IS (.{4})N(\d{6}).{10}(J| )\s*$/", 'parseHead'),
		'10' => array("/^10(\d{6})(.{32})\s*$/", 'parseCustomer'),
		'20' => array("/^20(\d{6})(.{10})\s*$/", 'parseIS'),
		'30' => array("/^30(\d{6})(.{10})(\d{6})\s*$/", 'parseDate'),
		'40' => array("/^40(.{25})(\d{13}).{7}(\d)(\d{10})(\d{8})(J| )\s*$/", 'parseTransaction'),
		'50' => array("/^50(\d{6})(.{10})(\d{6})(\d{7})(\d{15})\s*$/", "parseISfoot"),
		'90' => array("/^90(\d{6}).{10}(\d{6})(\d{7})(\d{15})\s*$/", "parseFoot"),
	);



	/* PARSING FUNCTIONS */


	/**
	 * @param string $agencyNr
	 * @param string $agencyName
	 * @param string $accountingUnit
	 * @param string $date
	 * @param string $reject
	 * @return bool TRUE if succesfull, FALSE on failure
	 */
	protected function parseHead($agencyNr, $agencyName, $accountingUnit, $date, $reject){
		$this->clearValues();
		$this->setValue('agency', $agencyNr, true);
		$this->setValue('agencyName', trim(utf8_encode($agencyName)), true);
		$this->setValue('accountingUnit', $accountingUnit, true);
		$this->setValue('date', $date, true);
		$this->setValue('rejectReg', $reject, true);
		return true;
	}


	/**
	 * @param string $customerNr
	 * @param string $customer
	 * @return bool TRUE if succesfull, FALSE on failure
	 */
	protected function parseCustomer($customerNr, $customer){
		if ( !$this->setValue('customer', trim(utf8_decode($customer)), true) ) {
			$this->error(_("Unvalid customer name"));
			return false;
		}
		return $this->setCustAndIs($customerNr);
	}


	/**
	 * @param string $customerNr
	 * @param string $ISnr
	 * @return bool TRUE if succesfull, FALSE on failure
	 */
	protected function parseIS($customerNr, $ISnr){
		if ( !$this->setValue('account', trim($ISnr)) ) {
			$this->error(_("Unvalid account"));
			return false;
		}
		return $this->setCustAndIs($customerNr);
	}


	/**
	 * @param string $customerNr
	 * @param string $ISnr
	 * @param string $date
	 * @return bool TRUE if succesfull, FALSE on failure
	 */
	protected function parseDate($customerNr, $ISnr, $date){
		if ( !$this->setValue('transactionDate', $date) ) {
			$this->error(_("Unvalid date"));
			return false;
		}
		return $this->setCustAndIs($customerNr, $ISnr);
	}


	/**
	 * @param string $ref
	 * @param string $amount
	 * @param string $senderCode
	 * @param string $sender
	 * @param string $nr
	 * @param string $reject
	 * @return bool TRUE if succesfull, FALSE on failure
	 */
	protected function parseTransaction($ref, $amount, $senderCode, $sender, $nr, $reject){
		$t = array(
			"ref" => trim(utf8_encode($ref)),
			"amount" => $this->str2amount($amount),
			"date" => $this->getValue('transactionDate'),
			"sender" => $sender,
			"senderCode" => $senderCode,
			"idNr" => $nr,
			"reject" => $reject,
		);
		$this->push($t);
		return true;
	}


	/**
	 * @param string $customerNr
	 * @param string $ISnr
	 * @param string $date
	 * @param string $nrTrans
	 * @param string $sumTrans
	 * @return bool TRUE if succesfull, FALSE on failure
	 */
	protected function parseISfoot($customerNr, $ISnr, $date, $nrTrans, $sumTrans){
		if ( !$this->setCustAndIs($customerNr, $ISnr) ) return false;

		if ( !$this->setValue('date', $date) ) {
			$this->error(_("Unvalid date"));
			return false;
		}

		$this->postCount += $this->count();
		$this->postSum += $this->sum('amount');

		if ( (int)$nrTrans != $this->count() ) {
			$this->error(_("Unvalid file content, wrong number of transaction posts."));
			return false;
		}

		if ( $this->str2amount($sumTrans) != $this->sum('amount') ) {
			$this->error(_("Unvalid file content, wrong transaction sum."));
			return false;
		}

		$this->writeSection();

		return true;
	}


	/**
	 * @param string $agencyNr
	 * @param string $date
	 * @param string $nrTrans
	 * @param string $sumTrans
	 * @return bool TRUE if succesfull, FALSE on failure
	 */
	protected function parseFoot($agencyNr, $date, $nrTrans, $sumTrans){
		if ( !$this->setValue('agency', $agencyNr) ) {
			$this->error(_("Agency number does not match."));
			return false;
		}
		if ( !$this->setValue('date', $date) ) {
			$this->error(_("Date does not match."));
			return false;
		}

		if ( (int)$nrTrans != $this->postCount ) {
			$this->error(_("Unvalid file content, wrong number of transaction posts."));
			return false;
		}

		if ( $this->str2amount($sumTrans) != $this->postSum ) {
			$this->error(_("Unvalid file content, wrong transaction sum."));
			return false;
		}
		
		return true;
	}



	/* HELPERS */


	/**
	 * Set customer number and is number
	 * @param string $customerNr
	 * @param string $ISnr
	 * @return bool TRUE on success, FALSE if an error occured
	 */
	private function setCustAndIs($customerNr, $ISnr=false){
		if ( !$this->setValue('customerNr', $customerNr, true) ) {
			$this->error(_("Unvalid customer number"));
			return false;
		}
		if ( $ISnr && !$this->setValue('account', trim($ISnr)) ) {
			$this->error(_("Unvalid account"));
			return false;
		}
		return true;
	}

}

?>
