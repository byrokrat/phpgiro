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
 * AG layout H, new consents from the bank.
 *
 * <code>
 * <b>Produces stack items with the following layout:</b>
 * [betNr] =>
 * [account] => account nr, regular or BG
 * [orgNr/persNr] => swedish social security number, or arganizational number
 * [status] => status code
 * [statusMsg] => string message describing status
 * [info] => array of addition info
 * [address] => array
 * </code>
 *
 * @package PhpGiro
 */
class PhpGiro_AG_H extends PhpGiro_AG_Object {

	/**
	 * Txt status messages
	 * @var array $statusMsgs
	 */
	protected $statusMsgs = array(
		0 => "Första meddelandet",
		1 => "Påminnelse nummer ett",
		2 => "Påminnelse nummer två",
	);

	/**
	 * Internal count of posts in section
	 * @var int $nrOfPosts
	 */
	private $nrOfPosts = 0;


	/**
	 * Extend sectionClear() to also clear posts in section
	 * @return void
	 */
	public function sectionClear(){
		$this->nrOfPosts = 0;
		return parent::sectionClear();
	}


	/* FILE STRUCTURE */


	/**
	 * Layout id
	 * @var string $layout
	 */
	protected $layout = 'H';


	/**
	 * Regex represention a valid file structure
	 * @var string $struct
	 */
	protected $struct = "/^(51(52(5[3456])*)+59)+$/";


	/**
	 * Map transaction codes (TC) to line-parsing regexp and receiving method
	 * @var array $map
	 */
	protected $map = array(
		'51' => array("/^51(\d{8})9900(\d{10})AG-EMEDGIV/", 'parseHeadDateBg'),
		'52' => array("/^52(\d{10})(\d{16})(\d{4})(\d{12})(\d{12}).{5}(\d)/", 'parseNewConsent'),
		'53' => array("/^53(.{0,36})/", 'parseInfo'),
		'54' => array("/^54(.{0,36})(.{0,36})/", 'parseAddress'),
		'55' => array("/^55(.{0,36})(.{0,36})/", 'parseAddress'),
		'56' => array("/^56(.{0,5})(.{0,31})/", 'parseAddress'),
		'59' => array("/^59(\d{8})9900(\d{7})/", 'parseFoot'),
	);


	/* PARSING FUNCTIONS */


	/**
	 * @param string $info
	 * @return bool TRUE on success, FALSE on failure
	 */
	protected function parseInfo($info){
		$this->nrOfPosts++;
		$info = trim(utf8_encode($info));
		$this->pushTo('info', $info);
		return true;
	}


	/**
	 * @param string $addr1
	 * @param string $addr2
	 * @return bool TRUE on success, FALSE on failure
	 */
	protected function parseAddress($addr1, $addr2=false){
		$this->nrOfPosts++;
		$addrs = func_get_args();
		foreach ( $addrs as $addr ) {
			$addr = trim(utf8_encode($addr));
			$this->pushTo('address', $addr);
		}
		return true;
	}


	/**
	 * @param string $bg
	 * @param string $betNr
	 * @param string $clearing
	 * @param string $account
	 * @param string $orgNr
	 * @param string $status
	 * @return bool TRUE on success, FALSE on failure
	 */
	protected function parseNewConsent($bg, $betNr, $clearing, $account, $orgNr, $status){
		if ( !$this->validBg($bg) ) return false;

		self::buildStateIdNr($orgNr, $orgNrType);

		$this->nrOfPosts++;

		$consent = array(
			'tc' => '52',
			'betNr' => ltrim($betNr, '0'),
			'account' => self::buildAccountNr($betNr, $clearing, $account),
			$orgNrType => $orgNr,
			'status' => $status,
			'statusMsg' => $this->statusMsgs[$status],
		);

		$this->push($consent);
		return true;
	}

	/**
	 * @param string $date
	 * @param string $nrPosts
	 * @return bool TRUE on success, FALSE on failure
	 */
	protected function parseFoot($date, $nrPosts){
		if ( !$this->validDate($date) ) return false;
		if ( (int)$nrPosts != $this->nrOfPosts ) {
			$this->error(sprintf(_("Unvalid file content, wrong number of type '%s' posts"), "52, 53, 54, 55 and 56"));
			return false;
		}
		$this->writeSection();
		return true;
	}

}

?>
