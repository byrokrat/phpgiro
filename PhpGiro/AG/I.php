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
 * AG layout I, list of transactions registered with the bank, but not processed.
 *
 * <code>
 * <b>Produces stack items with the following layout:</b>
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
 * @package PhpGiro
 */
class PhpGiro_AG_I extends PhpGiro_AG_Object {

	/* FILE STRUCTURE */


	/**
	 * Layout id
	 * @var string $layout
	 */
	protected $layout = 'I';


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
		'01' => array("/^01(\d{8})AUTOGIRO9900BEVAKNINGSREG.{27}(\d{6})(\d{10})/", 'parseHeadDateCustBg'),
		'82' => array("/^([83]2)(\d{8})(.)(...).(\d{16})(\d{12})/", 'parseTransaction'),
		'32' => array("/^([83]2)(\d{8})(.)(...).(\d{16})(\d{12})/", 'parseTransaction'),
		'09' => array("/^09(\d{8})9900.{14}(\d{12})(\d{6})(\d{6}).{4}(\d{12})/", 'parseTransactionFoot'),
	);

}

?>
