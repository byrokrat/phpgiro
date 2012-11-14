<?php
/**
 * This file is part of the swegiro package
 *
 * Copyright (c) 2012 Hannes Forsgård
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Hannes Forsgård <hannes.forsgard@gmail.com>
 * @package itbz\swegiro\Parser\Strategy\AG
 */

namespace itbz\swegiro\Parser\Strategy\AG;

use itbz\swegiro\Parser\AbstractStrategy;
use itbz\stb\Banking\Bankgiro;
use DateTime;

/**
 * Abstract autogiro strategy class
 *
 * @package itbz\swegiro\Parser\Strategy\AG
 */
abstract class AbstractAGStrategy extends AbstractStrategy
{
    /**
     * Receiving bankgiro account number
     *
     * @var Bankgiro
     */
    private $bgNr;

    /**
     * BGC customer number
     *
     * @var string
     */
    private $customerNr;

    /**
     * Date of parsed file
     *
     * @var DateTime
     */
    private $fileDate;

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
     * @param Bankgiro $bgNr
     *
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
     * @param string $customerNr
     *
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
     * @param DateTime $fileDate
     *
     * @return void
     */
    public function setFileDate(DateTime $fileDate)
    {
        $this->fileDate = $fileDate;
    }
}
