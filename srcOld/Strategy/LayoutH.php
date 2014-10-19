<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\autogiro\Strategy;

use ledgr\giro\Exception\StrategyException;
use ledgr\banking\Bankgiro;
use ledgr\id\CorporateIdBuilder;
use DateTime;

/**
 * Parser strategy for AG layout H (new electronic consents)
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class LayoutH extends AbstractStrategy
{
    /**
     * @var integer Counter for number of parsed posts
     */
    private $postCount;

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function getRegexpMap()
    {
        return array(
            '/^51(\d{8})9900(\d{10})AG-EMEDGIV/' => 'parseHead',
            '/^52(\d{10})(\d{16})(\d{4})(\d{12})(\d{12}).{5}(\d)/' => 'parseConsent',
            '/^53(.{0,36})/' => 'parseInfo',
            '/^54(.{0,36})(.{0,36})/' => 'parseAddress',
            '/^55(.{0,36})(.{0,36})/' => 'parseAddress',
            '/^56(.{0,5})(.{0,31})/' => 'parseAddress',
            '/^59(\d{8})9900(\d{7})/' => 'parseFoot'
        );
    }

    /**
     * {@inheritdoc}
     * 
     * @return void
     */
    public function clear()
    {
        $this->postCount = 0;
        parent::clear();
    }

    /**
     * Get the current post count
     *
     * @return int
     */
    public function getPostCount()
    {
        return $this->postCount;
    }

    /**
     * Parse file header
     *
     * @param  array $values
     * @return void
     */
    public function parseHead(array $values)
    {
        list($date, $bg) = $values;
        $this->setFileDate(new DateTime($date));
        $this->setBgNr(new Bankgiro(ltrim($bg, '0')));
    }

    /**
     * Parse new consent
     *
     * @param  array             $values 
     * @return void
     * @throws StrategyException If BG does not match file header
     */
    public function parseConsent(array $values)
    {
        list($bg, $betNr, $clearing, $account, $orgNr, $status) = $values;
        $this->postCount++;

        if ($this->getBgNr() != new Bankgiro(ltrim($bg, '0'))) {
            throw new StrategyException('BG number in consent does not match BG number in file.');
        }

        return;

        /*
            Ska jag använda CorporateIdBuilder här?
                kolla i dokumentationen vilka nummer som är möjliga här?
                är det möjligt att personnummer anänds som organisationsnummer
                i AG - filer??
         */

        self::buildStateIdNr($orgNr, $orgNrType);

        $consent = array(
            'tc' => '52',
            'betNr' => ltrim($betNr, '0'),
            'account' => self::buildAccountNr($betNr, $clearing, $account),
            $orgNrType => $orgNr,
            'status' => $status,
            'statusMsg' => $this->statusMsgs[$status],
        );

        $this->push($consent);
    }

    /**
     * Parse info
     *
     * @param  array $values
     * @return void
     */
    public function parseInfo(array $values)
    {
        list($info) = $values;
        $this->postCount++;
        $this->endAddress();
        $this->xmlWriter->writeElement('info', trim($info));
    }

    /**
     * Parse address
     *
     * @param  array $values
     * @return void
     */
    public function parseAddress(array $values)
    {
        $this->postCount++;

        if ($this->xmlWriter->currentElement() != 'address') {
            $this->xmlWriter->startElement('address');
        }

        foreach ($values as $addr) {
            $addr = trim($addr);
            $this->xmlWriter->writeElement('line', $addr);
        }
    }

    /**
     * End address element if open
     *
     * @return void
     */
    private function endAddress()
    {
        if ($this->xmlWriter->currentElement() == "address") {
            $this->xmlWriter->endElement();
        }
    }

    /**
     * Parse file footer
     *
     * @param  array             $values
     * @return void
     * @throws StrategyException If expected content is missing
     */
    public function parseFoot(array $values)
    {
        list($date, $nrPosts) = $values;
        $this->endAddress();

        if ((int)$nrPosts != $this->getPostCount()) {
            throw new StrategyException('Unvalid file content, wrong number of posts.');
        }

        if ($this->getFileDate() != new DateTime($date)) {
            throw new StrategyException('Footer date does not match header date.');
        }
    }
}
