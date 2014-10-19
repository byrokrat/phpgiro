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
 * Container for consents
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class ConsentContainer extends WritingContainer
{
    /**
     * Send new consent to bank
     *
     * @param LegalPerson $payer
     */
    public function register(LegalPerson $payer)
    {
        // TODO implement ConsentContainer::register()
    }

    /**
     * Remove registered consent
     *
     * @param LegalPerson $payer
     */
    public function remove(LegalPerson $payer)
    {
        // TODO use getAccount()->format()
        //      once banking is at 2.0

        // TODO use str_pad($payer->getId()->format('Ssk'), 16, '0', STR_PAD_LEFT)
        //      once billing allows installing id 2.0

        $this->addLine(
            '03'
            . str_pad(str_replace('-', '', $this->getPayee()->getAccount()), 10, '0', STR_PAD_LEFT)

            . '000000'.$payer->getId()->getDate()->format('ymd')
                . $payer->getId()->getIndividualNr()
                . $payer->getId()->getCheckDigit()

            . $payer->getAccount()->getClearing()
            . str_pad($payer->getAccount()->getNumber(), 12, '0', STR_PAD_LEFT)

            . str_repeat(' ', 36)
        );
    }

    /**
     * Approve electronic consent application
     *
     * @param LegalPerson $payer
     */
    public function approve(LegalPerson $payer)
    {
        // TODO implement ConsentContainer::approve()
    }

    /**
     * Reject electronic consent application
     *
     * @param LegalPerson $payer
     */
    public function reject(LegalPerson $payer)
    {
        // TODO implement ConsentContainer::reject()
    }
}
