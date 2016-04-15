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
 * AG layout F, feedback on rejected transactions.
 * @package PhpGiro
 */
class PhpGiro_AG_F extends PhpGiro_AG_Object {

	/**
	 * Messages describing transaction status
	 * @var array $statusMsgs
	 */
	protected $statusMsgs = array(
		1 => "Utgår, medgivande saknas.",
		2 => "Utgår, bankkontot är ännu ej godkänt, alternativt ännu ej debiterbart, alternativt avslutat.",
		3 => "Utgår, medgivande stoppat.",
		4 => "Felaktigt betalarnummer.",
		5 => "Felaktigt bankgironummer.",
		6 => "Felaktig periodkod.",
		7 => "Avvisad, bankkonto ännu ej debiterbart (autogiro privat), alternativt felaktigt antal självförnyande uppdrag (nya autogirot).",
		8 => "Beloppet är inte numeriskt.",
		9 => "Förbud mot utbetalningar.",
		10 => "Bankgironummret saknas hos Bankgirot.",
		12 => "Felaktigt betalningsdatum.",
		13 => "Passerat betalningsdatum.",
		15 => "Bankgironummret i öppningsposten och i transaktionsposten är inte detsamma.",
		24 => "Beloppet överstiger maxbeloppet.",
	);


	/* FILE STRUCTURE */

	/**
	 * Layout id
	 * @var string $layout
	 */
	protected $layout = 'F';


	/**
	 * Regex represention a valid file structure
	 * @var string $struct
	 */
	protected $struct = "/^(01([83]2)+09)+$/";


	/**
	 * Map transaction codes (TC) to line-parsing regexp and receiving method
	 * @var array $map
	 */
	protected $map = array(
		'01' => array("/^01(\d{8})AUTOGIRO9900FELLISTA REG.KONTRL.{21}(\d{6})(\d{10})/", 'parseHeadDateCustBg'),
		'82' => array("/^([83]2)(\d{8})(.)(...)(\d{16})(\d{12})()(.{16})(\d\d)/", 'parseTransaction'),
		'32' => array("/^([83]2)(\d{8})(.)(...)(\d{16})(\d{12})()(.{16})(\d\d)/", 'parseTransaction'),
		'09' => array("/^09(\d{8})9900(\d{6})(\d{12})(\d{6})(\d{12})/", 'parseTransactionFoot'),
	);


	/* PARSING FUNCTIONS */

	/**
	 * @param string $date
	 * @param string $nrCredit
	 * @param string $sumCredit
	 * @param string $nrInvoice
	 * @param string $sumInvoice
	 * @return bool TRUE on success, FALSE on failure
	 */
	protected function parseTransactionFoot($date, $nrCredit, $sumCredit, $nrInvoice, $sumInvoice){
		return parent::parseTransactionFoot($date, $sumCredit, $nrCredit, $nrInvoice, $sumInvoice);
	}

}

?>
