<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\autogiro\toBank\Record\Formatter;

/**
 * Format payer number
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class PayerNumberFormatter implements \ledgr\autogiro\toBank\Record\Formatter
{
    /**
     * The payer number is 16 digits long, right-aligned and zero-filled.
     *
     * @param  LegalPerson $person
     * @return string
     */
    public function format(\ledgr\billing\LegalPerson $person)
    {
        // TODO use str_pad($person->getId()->format('Ssk'), 16, '0', STR_PAD_LEFT) (Id 2.0)
        return '000000'
            . $person->getId()->getDate()->format('ymd')
            . $person->getId()->getIndividualNr()
            . $person->getId()->getCheckDigit();
    }
}
