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
 * Collection of mandate records
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class MandateFile extends File
{
    /**
     * Remove registered consent
     *
     * @param LegalPerson $debtor
     */
    public function remove(LegalPerson $debtor)
    {
        $this->addRecord(
            new Record\RemoveMandateRecord(
                $this->getCreditor(),
                $debtor,
                $this->getFormatters()
            )
        );
    }

    /**
     * Send new consent to bank
     *
     * @param LegalPerson $debtor
     */
    public function register(LegalPerson $debtor)
    {
        $this->addRecord(
            new Record\RegisterMandateRecord(
                $this->getCreditor(),
                $debtor,
                $this->getFormatters()
            )
        );
    }

    /**
     * Approve electronic consent application
     *
     * @param LegalPerson $debtor
     */
    public function approve(LegalPerson $debtor)
    {
        return $this->register($debtor);
    }

    /**
     * Reject electronic consent application
     *
     * @param LegalPerson $debtor
     */
    public function reject(LegalPerson $debtor)
    {
        $this->addRecord(
            new Record\RejectMandateRecord(
                $this->getCreditor(),
                $debtor,
                $this->getFormatters()
            )
        );
    }
}
