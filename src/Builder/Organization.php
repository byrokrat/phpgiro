<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\autogiro\Builder;

use ledgr\id\CorporateId;
use ledgr\banking\Bankgiro;

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
     * @var CorporateId Organization state id
     */
    private $corporateId;

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
     * @param string      $name        Organization name
     * @param CorporateId $corporateId Organization state id
     * @param string      $customerNr  Autogiro customer number (6 digits)
     * @param Bankgiro    $bankgiro    Bankgiro account number
     */
    public function __construct($name, CorporateId $corporateId, $customerNr, Bankgiro $bankgiro)
    {
        assert('is_string($name)');
        assert('is_numeric($customerNr) && 6==strlen($customerNr)');
        $this->name = $name;
        $this->corporateId = $corporateId;
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
     * Get organization state id
     *
     * @return CorporateId
     */
    public function getCorporateId()
    {
        return $this->corporateId;
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
