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
use iio\stb\Banking\AbstractAccount;

/**
 * Build autogiro files
 *
 * @author  Hannes Forsgård <hannes.forsgard@gmail.com>
 * @package swegiro
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
     * @var array List of new consents
     */
    private $addedConsents = array();

    /**
     * @var array List of consents to remove
     */
    private $removedConsents = array();

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
    }

    /**
     * Write consent to file
     *
     * @param  PersonalId      $id Id of added donor
     * @param  AbstractAccount $account Account of added donor
     * @return AgBuilder       instance for chaining
     */
    public function addConsent(PersonalId $id, AbstractAccount $account)
    {
        $this->addedConsents[] = array($id, $account);

        return $this;
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

        // Write new consents
        // TODO let addConsent write the complete line instead..
        // here only add arrays together...
        if (!empty($this->addedConsents)) {
            $phpgiro->addSection();
            foreach ($this->addedConsents as $consent) {
                list($id, $account) = $consent;
                $phpgiro->addConsent(
                    $id->getPayerNr(),
                    $account->getClearing(),
                    $account->getNumber(),
                    $id->getFullIdNoDelimiter()
                );
            }
        }

        $txt = $phpgiro->getFile();
        // TODO hack so that build returns array
        return explode("\r\n", $txt);

        // During development, while PhpGiro is used...
        if ($txt = $phpgiro->getFile()) {
            return explode("\r\n", $txt);
        } else {
            print_r($phpgiro->getErrors());
            throw new \iio\swegiro\Exception\ValidatorException('PhpGiro getFile error');
        }
    }
}
