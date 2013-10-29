<?php
/**
 * This file is part of the autogiro package
 *
 * Copyright (c) 2012-13 Hannes Forsgård
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iio\autogiro\Builder;

use iio\stb\Banking\Bankgiro;

/**
 * Organization data wrapper
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class Organization
{
    /**
     * @var string Organization name
     */
    private $name;

    /**
     * @var string Autogiro customer number
     */
    private $customerNr;

    /**
     * @var Bankgiro Bankgiro account number
     */
    private $bankgiro;

    /**
     * Organization data wrapper
     * @param string   $name       Organization name
     * @param string   $customerNr Autogiro customer number (6 digits)
     * @param Bankgiro $bankgiro   Bankgiro account number
     */
    public function __construct($name, $customerNr, Bankgiro $bankgiro)
    {
        assert('is_string($name)');
        assert('is_numeric($customerNr) && 6==strlen($customerNr)');
        $this->name = $name;
        $this->customerNr = $customerNr;
        $this->bankgiro = $bankgiro;
    }

    /**
     * Get organization name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get organization Autogiro Customer Number
     *
     * @return string
     */
    public function getAgCustomerNumber()
    {
        return $this->customerNr;
    }

    /**
     * Get Bankgiro account of organization
     *
     * @return Bankgiro
     */
    public function getBankgiro()
    {
        return $this->bankgiro;
    }
}