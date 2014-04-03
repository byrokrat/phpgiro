<?php
/**
 * This file is part of the autogiro package
 *
 * Copyright (c) 2012-13 Hannes Forsgård
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ledgr\autogiro\Strategy;

use ledgr\giro\StrategyInterface;
use ledgr\giro\XMLWriter;
use ledgr\banking\Bankgiro;
use DateTime;

/**
 * Abstract autogiro strategy class
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
abstract class AbstractStrategy implements StrategyInterface
{
    /**
     * @var XMLWriter XMLWriter object
     */
    protected $xmlWriter;

    /**
     * @var Bankgiro Receiving bankgiro account number
     */
    private $bgNr;

    /**
     * @var string BGC customer number
     */
    private $customerNr;

    /**
     * @var DateTime Date of parsed file
     */
    private $fileDate;

    /**
     * Constructor
     *
     * @param XMLWriter $xmlWriter
     */
    public function __construct(XMLWriter $xmlWriter)
    {
        $this->xmlWriter = $xmlWriter;
    }

    /**
     * Reset internal state
     * 
     * @return void
     */
    public function clear()
    {
        $this->xmlWriter->clear();
    }

    /**
     * Get created xml as a raw string
     *
     * @return string
     */
    public function getXML()
    {
        return $this->xmlWriter->getXml();
    }

    /**
     * Get receiving bankgiro account number
     *
     * @return Bankgiro
     */
    public function getBgNr()
    {
        return $this->bgNr;
    }

    /**
     * Set receiving bankgiro account number
     *
     * @param  Bankgiro $bgNr
     * @return void
     */
    public function setBgNr(Bankgiro $bgNr)
    {
        $this->bgNr = $bgNr;
    }

    /**
     * Get BGC customer number
     *
     * @return string
     */
    public function getCustomerNr()
    {
        return $this->customerNr;
    }

    /**
     * Set BGC customer number
     *
     * @param  string $customerNr
     * @return void
     */
    public function setCustomerNr($customerNr)
    {
        assert('is_string($customerNr)');
        $this->customerNr = $customerNr;
    }

    /**
     * Get date of parsed file
     *
     * @return DateTime
     */
    public function getFileDate()
    {
        return $this->fileDate;
    }

    /**
     * Set date of parsed file
     *
     * @param  DateTime $fileDate
     * @return void
     */
    public function setFileDate(DateTime $fileDate)
    {
        $this->fileDate = $fileDate;
    }
}
