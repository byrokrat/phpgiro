<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\autogiro\toBank\Record\Formatter;

use ledgr\billing\LegalPerson;

/**
 * Format id numbers
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class IdFormatter implements \ledgr\autogiro\toBank\Record\Formatter
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
