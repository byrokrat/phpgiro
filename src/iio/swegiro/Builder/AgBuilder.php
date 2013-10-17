<?php
/**
 * This file is part of the swegiro package
 *
 * Copyright (c) 2012-13 Hannes Forsgård
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iio\swegiro\Builder;

use DOMDocument;
use iio\swegiro\Swegiro;
use iio\swegiro\Organization;
use iio\swegiro\ID\PersonalId;
use iio\stb\Banking\AccountInterface;
use iio\stb\Utils\Amount;
use DateTime;

/**
 * Build autogiro files
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class AgBuilder
{
    /**
     * @var Swegiro Swegiro instance
     */
    private $giro;

    /**
     * @var Organization Current organization
     */
    private $org;

    /**
     * @var array Array of arrays, se clear()
     */
    private $data;

    /**
     * Build autogiro files
     *
     * @param Swegiro      $giro
     * @param Organization $org
     */
    public function __construct(Swegiro $giro, Organization $org)
    {
        $this->giro = $giro;
        $this->org = $org;
        $this->clear();
    }

    /**
     * Reset state
     *
     * @return void
     */
    public function clear()
    {
        $this->data = array(
            'addedConsents' => array(),
            'removedConsents' => array(),
            'bills' => array()
        );
    }

    /**
     * Write consent to file
     *
     * @param  PersonalId       $id Id of added donor
     * @param  AccountInterface $account Account of added donor
     * @return void
     */
    public function addConsent(PersonalId $id, AccountInterface $account)
    {
        $this->data['addedConsents'][] = array($id, $account);
    }

    /**
     * Bill donor once
     *
     * @param  PersonalId $id
     * @param  Amount     $amount
     * @param  DateTime   $date
     * @return void
     */
    public function bill(PersonalId $id, Amount $amount, DateTime $date)
    {
        $this->data['bills'][] = array($id, $amount, $date);
    }

    /**
     * Get generated contents in ag native format
     *
     * @return string
     */
    public function getNative()
    {
        $native = $this->build();
        $this->giro->convertToXML($native); // Validate native

        // TODO hack to make native return a string
        return implode("\r\n", $native);
    }

    /**
     * Get generated contents as XML
     * 
     * @return DOMDocument
     */
    public function getXML()
    {
        return $this->giro->convertToXML($this->build());
    }

    /**
     * Build native ag content
     *
     * @return string
     */
    private function build()
    {
        $phpgiro = new \PhpGiro_AG_ABC(
            $this->org->getAgCustomerNumber(),
            str_replace('-', '', $this->org->getBankgiro())   // TODO Hack to get bankgiro without dash
        );

        // TODO let addConsent write the complete line instead..
        if (!empty($this->data['addedConsents'])) {
            $phpgiro->addSection();
            foreach ($this->data['addedConsents'] as $consent) {
                /** @var \iio\swegiro\ID\PersonalId $id */
                /** @var \iio\stb\Banking\AccountInterface $account */
                list($id, $account) = $consent;
                $phpgiro->addConsent(
                    $id->getPayerNr(),
                    $account->getClearing(),
                    $account->getNumber(),
                    $id->getFullIdNoDelimiter()
                );
            }
        }

        if (!empty($this->data['bills'])) {
            $phpgiro->addSection();
            foreach ($this->data['bills'] as $bill) {
                /** @var \iio\swegiro\ID\PersonalId $id */
                /** @var \iio\stb\Utils\Amount $amount */
                /** @var \DateTime $date */
                list($id, $amount, $date) = $bill;
                $phpgiro->addInvoice(
                    $id->getPayerNr(),
                    (string) $amount,
                    $date->format('Ymd')
                );
            }
        }

        // TODO hack so that build returns array
        return explode("\r\n", $phpgiro->getFile());
    }
}
