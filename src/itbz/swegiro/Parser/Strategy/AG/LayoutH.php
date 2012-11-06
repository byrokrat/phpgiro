<?php
/**
 * This file is part of the swegiro package
 *
 * Copyright (c) 2012 Hannes Forsgård
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Hannes Forsgård <hannes.forsgard@gmail.com>
 * @package itbz\swegiro\Parser\Strategy\AG
 */

namespace itbz\swegiro\Parser\Strategy\AG;

use itbz\swegiro\Parser\AbstractStrategy;
use itbz\swegiro\Exception\ParserException;

/**
 * Parser strategy for AG layout H (new electronic consents)
 *
 * @package itbz\swegiro\Parser\Strategy\AG
 */
class LayoutH extends AbstractStrategy
{
    /**
     * Counter for number of parsed posts
     *
     * @var integer
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
            '/^51(\d{8})9900(\d{10})AG-EMEDGIV/' => 'parseHeadDateBg',
            '/^52(\d{10})(\d{16})(\d{4})(\d{12})(\d{12}).{5}(\d)/' => 'parseNewConsent',
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
     * Parse address
     *
     * @param array $addrLines
     *
     * @return void
     */
    public function parseAddress(array $addrLines)
    {
        $this->postCount++;

        foreach ($addrLines as $addr) {
            $addr = trim($addr);

            // Det här fungerar inte därför att jag inte vet vilket element som
            // är aktivt.....
            //
            // if ($this->xmlWriter->currentElement() != 'address') {
            //      $this->xmlWriter->startElement('address');
            // }
            //
            // räcker det för att hantera alla olika push to osv...??
            //      ja jag tror det
            //      det är några till meckiga grejer med sum osv. Men annars
            //      ska allt vara med...
            $this->xmlWriter->writeElement('line', $addr);

            // Det här är den gamla koden
            //$this->pushTo('address', $addr);
        }

        return true;
    }
}
