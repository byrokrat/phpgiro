<?php

namespace byrokrat\autogiro\toBank\Record\Formatter;

use byrokrat\billing\LegalPerson;

/**
 * Format id numbers
 */
class IdFormatter implements \byrokrat\autogiro\toBank\Record\Formatter
{
    /**
     * 12 digit number. Personal ids as YYYYMMDDNNNN, organizations as 00NNNNNNNNNN.
     *
     * @param  LegalPerson $person
     * @return string
     */
    public function format(LegalPerson $person)
    {
        // TODO must also be able to format orgnizations. orgnr can not format on Ymd
        return $person->getId()->getDate()->format('Ymd')
            . $person->getId()->getIndividualNr()
            . $person->getId()->getCheckDigit();
    }
}
