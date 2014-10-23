<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\autogiro\toBank\Record;

/**
 * Setters and getters for format objects
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class Formatters
{
    /**
     * @var Formatter Payer number formatter
     */
    private $payerNumberFormatter;

    /**
     * @var Formatter Id formatter
     */
    private $idFormatter;

    /**
     * @var Formatter Bankgiro formatter
     */
    private $bankgiroFormatter;

    /**
     * Set payer number formatter
     *
     * @param Formatter $formatter
     */
    public function setPayerNumberFormatter(Formatter $formatter)
    {
        $this->payerNumberFormatter = $formatter;
    }

    /**
     * Get payer number formatter
     *
     * @return Formatter
     */
    public function getPayerNumberFormatter()
    {
        if (!isset($this->payerNumberFormatter)) {
            $this->payerNumberFormatter = new Formatter\PayerNumberFormatter;
        }

        return $this->payerNumberFormatter;
    }

    /**
     * Set id formatter
     *
     * @param Formatter $formatter
     */
    public function setIdFormatter(Formatter $formatter)
    {
        $this->idFormatter = $formatter;
    }

    /**
     * Get id formatter
     *
     * @return Formatter
     */
    public function getIdFormatter()
    {
        if (!isset($this->idFormatter)) {
            $this->idFormatter = new Formatter\IdFormatter;
        }

        return $this->idFormatter;
    }

    /**
     * Set Bankgiro formatter
     *
     * @param Formatter $formatter
     */
    public function setBankgiroFormatter(Formatter $formatter)
    {
        $this->bankgiroFormatter = $formatter;
    }

    /**
     * Get Bankgiro formatter
     *
     * @return Formatter
     */
    public function getBankgiroFormatter()
    {
        if (!isset($this->bankgiroFormatter)) {
            $this->bankgiroFormatter = new Formatter\BankgiroFormatter;
        }

        return $this->bankgiroFormatter;
    }
}
