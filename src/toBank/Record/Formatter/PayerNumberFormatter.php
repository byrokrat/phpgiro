<?php

namespace byrokrat\autogiro\toBank\Record\Formatter;

use byrokrat\billing\LegalPerson;

/**
 * Format payer number
 */
class PayerNumberFormatter implements \byrokrat\autogiro\toBank\Record\Formatter
{
    /**
     * The payer number is 16 digits long, right-aligned and zero-filled.
     *
     * @param  LegalPerson $person
     * @return string
     */
    public function format(LegalPerson $person)
    {
        // TODO use str_pad($person->getId()->format('Ssk'), 16, '0', STR_PAD_LEFT) (Id 2.0)
        return '000000'
            . $person->getId()->getDate()->format('ymd')
            . $person->getId()->getIndividualNr()
            . $person->getId()->getCheckDigit();
    }
}
