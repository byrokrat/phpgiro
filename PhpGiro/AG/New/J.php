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
 * AG layout J, list of consents registered with the bank. New file layout.
 * @package PhpGiro
 */
class PhpGiro_AG_New_J extends PhpGiro_AG_J {

	/**
	 * Set parsing regexp for the new file layout
	 * @param string $customerNr
	 * @param string $bg
	 * @see setMap()
	 */
	public function __construct($customerNr=false, $bg=false){
		parent::__construct($customerNr, $bg);
		$this->regexp = array('/^(\d{10})(\d{12})(\d{16})(\d)(\d\d)(\d{8})(.{8})(\d)(.?)(.{0,5})(\d{0,4})(\d{0,12})/', 'parseConsent');
		$this->setMap();
	}

}

?>
