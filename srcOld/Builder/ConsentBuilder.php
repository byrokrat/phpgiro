<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\autogiro\Builder;

use ledgr\autogiro\Post\PostInterface;
use ledgr\billing\LegalPerson;

/**
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class ConsentBuilder implements PostInterface
{
    private $person, $org;

    public function __construct(LegalPerson $person, LegalPerson $org)
    {
        $this->person = $person;
        $this->org = $org;
    }

    public function getPerson()
    {
        return $this->person;
    }

    public function getRaw()
    {
        // TODO use improved format function of id and account...
        $id = $this->person->getId();

        return self::CONSENT_NEW

            // organization account (bankgiro)
            .str_pad(str_replace('-', '', $this->org->getAccount()), 10, '0', STR_PAD_LEFT)

            // payer number
            .'000000'.$id->getDate()->format('ymd').$id->getIndividualNr().$id->getCheckDigit()

            // account clearing
            .$this->person->getAccount()->getClearing()

            // account number
            .str_pad($this->person->getAccount()->getNumber(), 12, '0', STR_PAD_LEFT)

            // id
            .$id->getDate()->format('Ymd').$id->getIndividualNr().$id->getCheckDigit()

            // blank
            .str_pad("", 24);
    }
}
