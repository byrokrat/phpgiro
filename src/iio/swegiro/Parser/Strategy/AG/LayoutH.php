<?php
/**
 * This file is part of the swegiro package
 *
 * Copyright (c) 2012-13 Hannes Forsgård
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iio\swegiro\Parser\Strategy\AG;

use iio\swegiro\Exception\ParserException;
use iio\swegiro\Exception\ContentException;
use iio\stb\Banking\Bankgiro;
use iio\stb\ID\CorporateIdBuilder;
use DateTime;

/**
 * Parser strategy for AG layout H (new electronic consents)
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class LayoutH extends AbstractAGStrategy
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
     * @param  array            $values 
     * @return void
     * @throws ContentException If BG does not match file header
     */
    public function parseConsent(array $values)
    {
        list($bg, $betNr, $clearing, $account, $orgNr, $status) = $values;
        $this->postCount++;

        if ($this->getBgNr() != new Bankgiro(ltrim($bg, '0'))) {
            $msg = _('BG number in consent does not match BG number in file.');
            throw new ContentException($msg);
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
     * @param  array            $values
     * @return void
     * @throws ContentException If expected content is missing
     */
    public function parseFoot(array $values)
    {
        list($date, $nrPosts) = $values;
        $this->endAddress();

        if ((int)$nrPosts != $this->getPostCount()) {
            $msg = _('Unvalid file content, wrong number of posts.');
            throw new ContentException($msg);
        }

        if ($this->getFileDate() != new DateTime($date)) {
            $msg = _('Footer date does not match header date.');
            throw new ContentException($msg);
        }
    }
}
