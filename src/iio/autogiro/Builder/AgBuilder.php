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

use DOMDocument;
use iio\giro\Giro;
use iio\autogiro\Organization;
use iio\stb\ID\PersonalId;
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
     * @var Giro Giro instance
     */
    private $giro;

    /**
     * @var Organization Current organization
     */
    private $org;

    /**
     * @var AgConverter Converter for ag formats
     */
    private $converter;

    /**
     * @var array Array of arrays, se clear()
     */
    private $data;

    /**
     * Build autogiro files
     *
     * @param Giro         $giro
     * @param Organization $org
     * @param AgConverter  $converter
     */
    public function __construct(Giro $giro, Organization $org, AgConverter $converter = null)
    {
        $this->giro = $giro;
        $this->org = $org;
        $this->converter = $converter ?: new AgConverter;
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
        
        // Validate native
        $this->giro->convertToXML($native);

        // Return string
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
     * @return array
     */
    private function build()
    {
        $phpgiro = new \PhpGiro_AG_ABC(
            $this->org->getAgCustomerNumber(),
            $this->converter->convertBankgiro($this->org->getBankgiro())
        );

        // TODO let addConsent write the complete line instead..
        if (!empty($this->data['addedConsents'])) {
            $phpgiro->addSection();
            foreach ($this->data['addedConsents'] as $consent) {
                /** @var \iio\stb\ID\PersonalId $id */
                /** @var \iio\stb\Banking\AccountInterface $account */
                list($id, $account) = $consent;
                $phpgiro->addConsent(
                    $this->converter->convertPayerNr($id),
                    $account->getClearing(),
                    $account->getNumber(),
                    $this->converter->convertId($id)
                );
            }
        }

        if (!empty($this->data['bills'])) {
            $phpgiro->addSection();
            foreach ($this->data['bills'] as $bill) {
                /** @var \iio\stb\ID\PersonalId $id */
                /** @var \iio\stb\Utils\Amount $amount */
                /** @var \DateTime $date */
                list($id, $amount, $date) = $bill;
                $phpgiro->addInvoice(
                    $this->converter->convertPayerNr($id),
                    (string) $amount,
                    $date->format('Ymd')
                );
            }
        }

        // TODO hack so that build returns array
        return explode("\r\n", $phpgiro->getFile());
    }
}
