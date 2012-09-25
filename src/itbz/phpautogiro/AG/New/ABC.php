<?php
/**
 * This file is part of the STB package
 *
 * Copyright (c) 2011-12 Hannes Forsgård
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Hannes Forsgård <hannes.forsgard@gmail.com>
 *
 * @package STB\Giro\Ag
 */


/**
 * Added functinality for creating A, B or C layouted files using new layout.
 * 
 * This class is not needed for parsing new A, B or C files.
 *
 * @package STB\Giro\Ag
 */
class PhpGiro_AG_New_ABC extends PhpGiro_AG_ABC
{

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
