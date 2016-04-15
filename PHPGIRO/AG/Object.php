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
 * Autogiro base class
 * @package PhpGiro
 */
abstract class PhpGiro_AG_Object extends PhpGiro_Char80 {


	/**
	 * Each AgObject only works with one BGC customer number and
	 * one BG account number. If you try to parse AG files intended for
	 * other customer numbers or BG account numbers errors will be raised.
	 * @param string $customerNr
	 * @param string $bg
	 */
	public function __construct($customerNr=false, $bg=false){
		$this->sectionClear();
		if ( $customerNr ) {
			if ( strlen($customerNr) > 6 ) $this->error(_("Customer number to long."));
			$customerNr = str_pad($customerNr, 6, '0', STR_PAD_LEFT);
			$this->setValue('customerNr', $customerNr, true);
		}
		if ( $bg ) {
			if ( strlen($bg) > 10 ) $this->error(_("BG number to long."));
			$bg = str_pad($bg, 10, '0', STR_PAD_LEFT);
			$this->setValue('bg', $bg, true);
		}
	}


	/**
	 * Validate bg number
	 * @param string $bg
	 * @return bool
	 */
	protected function validBg($bg){
		$bg = ltrim($bg, '0');
		if ( $this->setValue('bg', $bg) ) {
			return true;
		} else {
			$this->error(_("Unvalid BG number"));
			return false;
		}
	}


	/**
	 * Validate customer number
	 * @param string $nr
	 * @return bool
	 */
	protected function validCustomerNr($nr){
		$nr = ltrim($nr, '0');
		if ( $this->setValue('customerNr', $nr) ) {
			return true;
		} else {
			$this->error(_("Unvalid customer number"));
			return false;
		}
	}


	/**
	 * Get date parsed file was created, YYYYMMDD
	 * @return string
	 */
	public function getDate(){
		return $this->getValue('date');
	}


	/**
	 * Set date, YYYYMMDD
	 * @param string $date
	 * @return string Date set
	 */
	protected function setDate($date){
		if ( strlen($date) == 8 ) {
			$this->setValue('date', $date);
		}
		return $this->getValue('date');
	}


	/**
	 * Validate customer number
	 * @param string $date
	 * @return bool
	 */
	protected function validDate($date){
		if ( $date != $this->values['date'] ) {
			$this->error(_("Unvalid creation date"));
			return false;
		} else {
			return true;
		}
	}



	/* SECTIONS */


	/**
	 * Create a section object and store it. Clear internal representation
	 * @param string $layout
	 * @return void
	 */
	protected function writeSection($layout=false){
		if ( !$layout ) $layout = $this->layout;

		$s = array(
			'layout' => $layout,
			'layoutName' => $this->layoutNames[$layout],
		);

		$s = array_merge($s, $this->values);

		$s['errors'] = $this->getErrors();
		$s['posts'] = $this->getStack();

		//save section
		array_push($this->sections, $s);

		//clear for next section
		$this->sectionClear();
	}



	/* PARSING FUNCTIONS */


	/**
	 * Parse header post (TC == 01) containing file $date, $customerNr and
	 * recieving $bg number. Validates $bg and $customerNr, set $date.
	 * @param string $date
	 * @param string $customerNr
	 * @param string $bg
	 * @return FALSE if $bg or $customerNr is not valid, TRUE otherwise
	 */
	protected function parseHeadDateCustBg($date, $customerNr, $bg){
		if ( !$this->validBg($bg) ) return false;
		if ( !$this->validCustomerNr($customerNr) ) return false;
		$this->setDate($date);
		return true;
	}


	/**
	 * Parse header post (TC == 01) containing file $date and
	 * recieving $bg number. Validates $bg and sets $date.
	 * @param string $date
	 * @param string $bg
	 * @return FALSE if $bg is not valid, TRUE otherwise
	 */
	protected function parseHeadDateBg($date, $bg){
		if ( !$this->validBg($bg) ) return false;
		$this->setDate($date);
		return true;
	}


	/**
	 * Parse transaction post.
	 *
	 * <code>
	 * <b>Pushes an array to the stack with the following layout:</b>
	 * [transType] => I=invoice, C=credit
	 * [date] => date when BGC proccessed the transaction
	 * [betNr] =>
	 * [amount] =>
	 * [ref] => custom reference
	 * [period] => repitition cycles code
	 * [periodMsg] => string describing repitition period
	 * [repetitions] => nr of repititions left, -1 means infinite
	 * [status] => transaction code
	 * [statusMsg] => string message describing status code
	 * </code>
	 *
	 * @param string $tc
	 * @param string $date
	 * @param string $period
	 * @param string $repetitions
	 * @param string $betNr
	 * @param string $amount
	 * @param string $bg
	 * @param string $ref
	 * @param string $status
	 * @return bool TRUE on success, FALSE on error
	 */
	protected function parseTransaction($tc, $date, $period, $repetitions, $betNr, $amount, $bg=false, $ref=false, $status=false){
		if ( $bg && !$this->validBg($bg) ) return false;

		if ( preg_match("/^\s*$/", $repetitions) ) {
			$repetitions = ( $period == 0 ) ? 1 : -1;
		}

		$t = array(
			'transType' => ( $tc=='82' ) ? 'I' : 'C',
			'date' => trim($date),
			'betNr' => ltrim($betNr, '0'),
			'amount' => $this->str2amount($amount),
			'ref' => trim(utf8_encode($ref)),
			'period' => $period,
			'periodMsg' => $this->periodMsgs[$period],
			'repetitions' => ltrim($repetitions, '0'),
		);

		if ( $status ) {
			if ( $status==" " ) $status = 0;
			$status = (int)$status;
			$t['status'] = $status;
			$t['statusMsg'] = $this->statusMsgs[$status];
		}
		
		$this->push($t);
		return true;
	}


	/**
	 * Parse transaction footer. Validate date. Validate nr of posts,
	 * and post sums. Write section.
	 * @param string $date
	 * @param string $sumCredit
	 * @param string $nrCredit
	 * @param string $nrInvoice
	 * @param string $sumInvoice
	 * @return bool TRUE on success, FALSE on error
	 */
	protected function parseTransactionFoot($date, $sumCredit, $nrCredit, $nrInvoice, $sumInvoice){
		if ( !$this->validDate($date) ) return false;
		
		//Validate nr of posts
		if ( (int)$nrCredit != $this->count('transType', 'C') ) {
			$this->error(sprintf(_("Unvalid file content, wrong number of type '%s' posts"), "32"));
			return false;
		}
		if ( (int)$nrInvoice != $this->count('transType', 'I') ) {
			$this->error(sprintf(_("Unvalid file content, wrong number of type '%s' posts"), "82"));
			return false;
		}

		//Validate sums
		if ( $this->str2amount($sumCredit) != $this->sum('amount', array('transType', 'C')) ) {
			$this->error(sprintf(_("Unvalid file content, wrong sum total for '%s' posts"), "32"));
			return false;
		}
		if ( $this->str2amount($sumInvoice) != $this->sum('amount', array('transType', 'I')) ) {
			$this->error(sprintf(_("Unvalid file content, wrong sum total for '%s' posts"), "82"));
			return false;
		}

		$this->writeSection();
		return true;
	}



	/* MESSAGES */

	
	/**
	 * Messages describing periodic transactions
	 * @var array $periodMsgs
	 */
	protected $periodMsgs = array(
		0 => "En gång",
		1 => "En gång per månad, på bankdag som anges i betalningsposten.",
		2 => "En gång per kvartal, på bankdag som anges i betalningsposten.",
		3 => "En gång per halvår, på bankdag som anges i betalningsposten.",
		4 => "En gång per år, på bankdag som anges i betalningsposten.",
		5 => "En gång per månad, på sista bankdagen i månaden.",
		6 => "En gång per kvartal, på sista bankdagen i månaden.",
		7 => "En gång per halvår, på sista bankdagen i månaden.",
		8 => "En gång per år, på sista bankdagen i månaden.",
	);


	/**
	 * Messages describing transaction status
	 * @var array $statusMsgs
	 */
	protected $statusMsgs = array(
		0 => "Godkänd, betalningen genomförd.",
		1 => "Täckning saknas, betalningen har inte genomförts.",
		2 => "Koppling till Autogiro saknas (bankkontot avslutat), betalningen har inte genomförts.",
		9 => "Förnyad täckning, betalningen har inte genomförts men nytt försök ska göras om avtal finns.",
	);


	/**
	 * Layout names
	 * @var array $layoutNames
	 */
	protected $layoutNames = array(
		'A' => "Medgivandeunderlag",
		'B' => "Betalningsunderlag",
		'C' => "Makuleringar/ändringar av betalningsunderlag",
		'D' => "Betalningsspecifikation",
		'E' => "Medgivandeavisering",
		'F' => "Avvisade betalningsuppdrag",
		'G' => "Avisering makuleringar/ändringar",
		'H' => "Medgivanden via Internetbanken",
		'I' => "Utrag ur bevakningsregistret",
		'J' => "Utdrag ur medgivanderegistret",
	);


	/**
	 * TC names
	 * @var array $tcNames
	 */
	protected $tcNames = array(
		3 => "Makulerad på grund av borttaget medgivande.",
		4 => "Nytt medgivande.",
		5 => "Byte av betalarnummer.",

		11 => "Makulerad av betalare.",

		21 => "Makulera alla betalningar för betalarnummer=bankkontonummer.",
		22 => "Makulera alla betalningar på angiven dag för betalarnummer=bankkontonummer.",
		23 => "Makulera alla betalningar för ett betalarnummer.",
		24 => "Makulera alla betalningar för ett betalarnummer inom en angiven förfallodag.",
		25 => "Makulera en enstaka betalning.",

		26 => "Ändra alla betalningar till ny förfallodag.",
		27 => "Ändra alla betalningar från angiven förfallodag till ny förfallodag.",
		28 => "Ändra alla betalningar inom ett betalarnummer från angiven förfallodag till ny förfallodag.",
		29 => "Ändra en betalning från angiven förfallodag till ny förfallodag.",
	);



	/* STATIC PARSER HELPERS */


	/**
	 * Get account number. If account is filled with zeros $betNr is treated as
	 * a BG account number. Else $clearing and $account is concatenated,
	 * separated with a comma (,).
	 * @param string $betNr
	 * @param string $clearing
	 * @param string $account
	 */
	protected static function buildAccountNr($betNr, $clearing, $account){
		$betNr = ltrim($betNr, '0');
		$clearing = trim($clearing);
		$account = trim($account);
		if ( (strlen($betNr)==7 || strlen($betNr)==8) && (empty($clearing) && empty($account)) || preg_match("/^0+$/", $account) ) {
			$account = $betNr;
		} else {
			$account = ltrim($account, '0');
			$account = trim($account);
			$clearing = trim($clearing);
			if ( !empty($clearing) ) $account = "$clearing,$account";
		}
		return $account;
	}


	/**
	 * Get state id nr, eg. swedish social security number, or
	 * organizational number.
	 * @param string $nr 
	 * @param string $type Will contain either the string 'persNr' or the string 'orgNr'
	 */
	protected static function buildStateIdNr(&$nr, &$type){
		if ( !empty($nr) ) {
			if ( preg_match("/^[09]{2}(\d{10})$/", $nr, $match) ) {
				$type = "orgNr";
				$nr = $match[1];
			} else {
				$type = "persNr";
			}
		}
	}

}

?>
