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
     * Section consent flag
     */
    const SECTION_CONSENT = 1;

    /**
     * Section invoice flag
     */
    const SECTION_INVOICE = 2;

    /**
     * @var Swegiro Swegiro instance
     */
    private $giro;

    /**
     * @var Organization Current organization
     */
    private $org;

    /**
      * @var integer Flag current writing section
     */
    private $currentSection = 0;

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
        // TODO should load content and build everything at build instead
        if (!isset($this->phpgiro)) {
            $this->phpgiro = new \PhpGiro_AG_ABC(
                $this->org->getAgCustomerNumber(),
                str_replace('-', '', $this->org->getBankgiro())   // TODO Hack to get bankgiro without dash
            );
        }

        if ($this->currentSection != self::SECTION_CONSENT) {
            $this->phpgiro->addSection();
            $this->currentSection = self::SECTION_CONSENT;
        }

        $this->phpgiro->addConsent(
            $id->getPayerNr(),
            $account->getClearing(),
            $account->getNumber(),
            $id->getFullIdNoDelimiter()
        );

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
        $this->giro->validateNative($this->buildArray($native));

        return $native;
    }

    /**
     * Get generated contents as XML
     * 
     * @return DOMDocument
     */
    public function getXML()
    {
        return $this->giro->convertToXML($this->buildArray());
    }

    /**
     * Build native ag content
     *
     * @return string
     */
    private function build()
    {
        // hack until phpgiro transition, see above...
        if (!isset($this->phpgiro)) {
            return '';
        }

        if ($txt = $this->phpgiro->getFile()) {
            return $txt;
        } else {
            // TODO Global die on error. Should verify with Swegiro instead
            print_r($this->phpgiro->getErrors());
            throw new \iio\swegiro\Exception('PhpGiro getFile error');
        }
    }

    /**
     * Build native ag content to array
     *
     * @param  string $native If omitted a new build will be triggered
     * @return aray
     */
    private function buildArray($native = '')
    {
        if (empty($native)) {
            $native = $this->build();
        }

        return explode("\r\n", $native);
    }
}
