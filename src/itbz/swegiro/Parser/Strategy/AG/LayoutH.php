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
use itbz\swegiro\Exception\ContentException;

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
     * Get the current post count
     *
     * @return int
     */
    public function getPostCount()
    {
        return $this->postCount;
    }

    /**
     * Parse address
     *
     * @param array $values
     *
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
     * Parse info
     *
     * @param array $values
     *
     * @return void
     */
    public function parseInfo(array $values)
    {
        list($info) = $values;
        $this->postCount++;
        $this->endAddress();
        $this->xmlWriter->writeElement('info', trim($info));
    }


    /*
        parseHeadDateBg till AbstractStrategy..
        parseFoot måste portas till min nya kod
        parseNewConsent har jag inte börjat med alls...
     */


    /**
     * Parse file footer
     *
     * @param array $values
     *
     * @return void
     */
    public function parseFoot(array $values)
    {
        list($date, $nrPosts) = $values;
        $this->endAddress();

        if ((int)$nrPosts != $this->getPostCount()) {
            $msg = _('Unvalid file content, wrong number of posts.');
            throw new ContentException($msg);
        }

        // TODO Vad händer här? Måste vara samma datum som i header?
        if (!$this->validDate($date)) {
            return false;
        }


        // TODO Vad händer här??
        $this->writeSection();
    }
}
