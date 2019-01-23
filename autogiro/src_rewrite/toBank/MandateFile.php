<?php

namespace byrokrat\autogiro\toBank;

use byrokrat\billing\LegalPerson;

/**
 * Collection of mandate records
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
