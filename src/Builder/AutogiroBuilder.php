<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\autogiro\Builder;

use DOMDocument;
use ledgr\giro\Giro;
use ledgr\autogiro\AutogiroFactory;
use ledgr\billing\Invoice;
use ledgr\billing\LegalPerson;
use DateTime;

/**
 * Build autogiro files
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class AutogiroBuilder
{
    /**
     * @var Giro Giro instance
     */
    private $giro;

    /**
     * @var LegalPerson Current organization
     */
    private $org;

    /**
     * @var AutogiroConverter Converter for ag formats
     */
    private $converter;

    /**
     * @var array Array of arrays, se clear()
     */
    private $data;

    public function __construct(LegalPerson $org, Giro $giro = null, AutogiroConverter $converter = null)
    {
        $this->org = $org;
        $this->giro = $giro ?: new Giro(new AutogiroFactory);
        $this->converter = $converter ?: new AutogiroConverter;
        $this->clear();
    }

    public function clear()
    {
        $this->data = array(
            'addedConsents' => array(),
            'removedConsents' => array(),
            'invoices' => array()
        );
    }

    public function addConsent(LegalPerson $person)
    {
        $this->data['addedConsents'][] = $person;
    }

    public function addInvoice(Invoice $invoice)
    {
        $this->data['invoices'][] = $invoice;
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
            $this->org->getCustomerNumber(),
            $this->converter->convertBankgiro($this->org->getAccount())
        );

        // TODO let addConsent write the complete line instead..
        if (!empty($this->data['addedConsents'])) {
            $phpgiro->addSection();
            /** @var \ledgr\billing\LegalPerson $person */
            foreach ($this->data['addedConsents'] as $person) {
                $phpgiro->addConsent(
                    $this->converter->convertPayerNr($person->getId()),
                    $person->getAccount()->getClearing(),
                    $person->getAccount()->getNumber(),
                    $this->converter->convertId($person->getId())
                );
            }
        }

        if (!empty($this->data['invoices'])) {
            $phpgiro->addSection();
            /** @var \ledgr\billing\Invoice $invoice */
            foreach ($this->data['invoices'] as $invoice) {
                // TODO validate that $invoice->getSeller() and $this->org are the same...
                $phpgiro->addInvoice(
                    $this->converter->convertPayerNr($invoice->getBuyer()->getId()),
                    (string) $invoice->getInvoiceTotal(),
                    $invoice->getExpirationDate()->format('Ymd')
                );
            }
        }

        // TODO hack so that build returns array
        return explode("\r\n", $phpgiro->getFile());
    }
}
