<?php
/**
 * This file is part of the autogiro package
 *
 * Copyright (c) 2012-13 Hannes Forsgård
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iio\autogiro;

use iio\giro\Exception;
use iio\stb\Banking\PlusGiro;
use iio\stb\Banking\Bankgiro;

/**
 * Organization data wrapper
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class Organization
{
    /**
     * @var Bankgiro Organization Bankgiro number
     */
    private $bankgiro;

    /**
     * @var PlusGiro Organization PlusGiro number
     */
    private $plusgiro;

    /**
     * @var string Organization name
     */
    private $name;

    /**
     * @var string Organization Autogiro customer number
     */
    private $agCustomerNumber;

    /**
     * Set organization Bankgiro
     *
     * @param  Bankgiro $bankgiro
     * @return void
     */
    public function setBankgiro(Bankgiro $bankgiro)
    {
        $this->bankgiro = $bankgiro;
    }

    /**
     * Get organization Bankgiro
     *
     * @return Bankgiro
     * @throws Exception If Bankgiro is not set
     */
    public function getBankgiro()
    {
        if (!isset($this->bankgiro)) {
            throw new Exception('Bankgiro not set');
        }

        return $this->bankgiro;
    }

    /**
     * Set organization PlusGiro
     *
     * @param  PlusGiro $plusgiro
     * @return void
     */
    public function setPlusGiro(PlusGiro $plusgiro)
    {
        $this->plusgiro = $plusgiro;
    }

    /**
     * Get organization PlusGiro
     *
     * @return PlusGiro
     * @throws Exception If PlusGiro is not set
     */
    public function getPlusGiro()
    {
        if (!isset($this->plusgiro)) {
            throw new Exception('PlusGiro not set');
        }

        return $this->plusgiro;
    }

    /**
     * Set organization name
     *
     * @param  string $name
     * @return void
     */
    public function setName($name)
    {
        assert('is_string($name)');
        $this->name = $name;
    }

    /**
     * Get organization name
     *
     * @return string
     * @throws Exception If name is not set
     */
    public function getName()
    {
        if (!isset($this->name)) {
            throw new Exception('Name not set');
        }

        return $this->name;
    }

    /**
     * Set organization Autogiro Customer Number
     *
     * @param  string $agCustomerNumber
     * @return void
     */
    public function setAgCustomerNumber($agCustomerNumber)
    {
        assert('is_string($agCustomerNumber) && ctype_digit($agCustomerNumber)');
        $this->agCustomerNumber = $agCustomerNumber;
    }

    /**
     * Get organization Autogiro Customer Number
     *
     * @return string
     * @throws Exception If Autogiro Customer Number is not set
     */
    public function getAgCustomerNumber()
    {
        if (!isset($this->agCustomerNumber)) {
            throw new Exception('Autogiro Customer Number not set');
        }

        return $this->agCustomerNumber;
    }
}
