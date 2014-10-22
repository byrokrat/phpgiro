<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\autogiro\toBank;

use ledgr\billing\LegalPerson;

/**
 * Collection of mandates
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class MandateFile extends File
{
    /**
     * Send new consent to bank
     *
     * @param LegalPerson $debtor
     */
    public function register(LegalPerson $debtor)
    {
        // TODO implement ConsentContainer::register()
    }

    /**
     * Remove registered consent
     *
     * @param LegalPerson $debtor
     */
    public function remove(LegalPerson $debtor)
    {
        // TODO use getAccount()->format()
        //      once banking is at 2.0

        // TODO use str_pad($debtor->getId()->format('Ssk'), 16, '0', STR_PAD_LEFT)
        //      once billing allows installing id 2.0

        $this->addLine(
            '03'
            . str_pad(str_replace('-', '', $this->getCreditor()->getAccount()), 10, '0', STR_PAD_LEFT)

            . '000000'.$debtor->getId()->getDate()->format('ymd')
                . $debtor->getId()->getIndividualNr()
                . $debtor->getId()->getCheckDigit()

            . $debtor->getAccount()->getClearing()
            . str_pad($debtor->getAccount()->getNumber(), 12, '0', STR_PAD_LEFT)

            . str_repeat(' ', 36)
        );
    }

    /**
     * Approve electronic consent application
     *
     * @param LegalPerson $debtor
     */
    public function approve(LegalPerson $debtor)
    {
        // TODO implement ConsentContainer::approve()
    }

    /**
     * Reject electronic consent application
     *
     * @param LegalPerson $debtor
     */
    public function reject(LegalPerson $debtor)
    {
        // TODO implement ConsentContainer::reject()
    }
}
