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
 * Handles payee and the bgc file format
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class WritingContainer implements ContainerInterface
{
    /**
     * Bank format end of line character
     */
    const EOL = "\r\n";

    /**
     * @var LegalPerson Payment recipient
     */
    private $payee;

    /**
     * @var string[] Data in bank format
     */
    private $lines = [];

    /**
     * Register payment recipient
     *
     * @param \DateTime $date
     * @param LegalPerson $payee
     */
    public function __construct(LegalPerson $payee, \DateTime $date = null)
    {
        // TODO update once LegalPerson is an interface in billing..
        $this->payee = $payee;
        $this->addLine($this->createOpeningRecord($date));
    }

    /**
     * Get contained transactions and assignments in the bank format
     *
     * @return string ISO-8859-1 encoded string
     */
    public function createBankData()
    {
        return iconv(
            "UTF-8",
            "ISO-8859-1",
            implode(self::EOL, $this->lines).self::EOL
        );
    }

    public function addLine($line)
    {
        $this->lines[] = $line;
    }

    /**
     * Get payee
     *
     * @return LegalPerson
     */
    public function getPayee()
    {
        return $this->payee;
    }

    /**
     * Create opening record for this payee
     *
     * @param  \DateTime $date
     * @return string UTF-8 encoded string
     */
    public function createOpeningRecord(\DateTime $date = null)
    {
        $date = $date ?: new \DateTime;
        // TODO use $this->payee->getAccount()->format() once banking is at 2.0
        return '01'
            . $date->format('Ymd')
            . 'AUTOGIRO'
            . str_repeat(' ', 44)
            . str_pad($this->payee->getCustomerNumber(), 6, '0', STR_PAD_LEFT)
            . str_pad(str_replace('-', '', $this->payee->getAccount()), 10, '0', STR_PAD_LEFT)
            . str_repeat(' ', 2);
    }
}
