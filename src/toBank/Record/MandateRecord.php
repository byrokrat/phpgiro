<?php

namespace byrokrat\autogiro\toBank\Record;

use byrokrat\billing\LegalPerson;

/**
 * Mandate base record
 */
abstract class MandateRecord implements \byrokrat\autogiro\toBank\Record
{
    /**
     * @var LegalPerson Payment recipient
     */
    protected $creditor;

    /**
     * @var LegalPerson Payment sender
     */
    protected $debtor;

    /**
     * @var Formatters Collection of formatters
     */
    protected $formatters;

    /**
     * Create record
     *
     * @param LegalPerson $creditor   Payment recipient
     * @param LegalPerson $debtor     Payment sender
     * @param Formatters  $formatters Collection of formatters
     */
    public function __construct(LegalPerson $creditor, LegalPerson $debtor, Formatters $formatters)
    {
        $this->creditor = $creditor;
        $this->debtor = $debtor;
        $this->formatters = $formatters;
    }
}
