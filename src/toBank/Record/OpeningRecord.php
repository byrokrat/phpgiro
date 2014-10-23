<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\autogiro\toBank\Record;

use ledgr\billing\LegalPerson;

/**
 * Each bgc file (or section) must start with an opening record
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class OpeningRecord implements \ledgr\autogiro\toBank\Record
{
    /**
     * @var LegalPerson Payment recipient
     */
    private $creditor;

    /**
     * @var \DateTime Date of file creation
     */
    private $date;

    /**
     * @var Formatters Formatters collection
     */
    private $formatters;

    /**
     * Create record
     *
     * @param LegalPerson $creditor   Payment recipient
     * @param \DateTime   $date       Date of file creation
     * @param Formatters  $formatters Formatters collection
     */
    public function __construct(LegalPerson $creditor, \DateTime $date, Formatters $formatters)
    {
        $this->creditor = $creditor;
        $this->date = $date;
        $this->formatters = $formatters;
    }

    /**
     * Get record as string
     *
     * @return string
     */
    public function getRecord()
    {
        return '01'
            . $this->date->format('Ymd')
            . 'AUTOGIRO'
            . str_repeat(' ', 44)
            . str_pad($this->creditor->getCustomerNumber(), 6, '0', STR_PAD_LEFT)
            . $this->formatters->getBankgiroFormatter()->format($this->creditor)
            . str_repeat(' ', 2);
    }
}
