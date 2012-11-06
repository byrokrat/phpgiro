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
 * @package itbz\swegiro\Parser
 */

namespace itbz\swegiro\Parser;

use itbz\Exception\ParserException;
use itbz\swegiro\XMLWriter;

/**
 * Abstract parser strategy class
 *
 * @package itbz\swegiro\Parser
 */
abstract class AbstractStrategy
{
    /**
     * XMLWriter object
     *
     * @var XMLWriter
     */
    protected $xmlWriter;

    /**
     * Get array of parsing regular expressions maped to parser methods
     *
     * @return array
     */
    abstract public function getRegexpMap();

    /**
     * Inject xml writer object
     *
     * @param XMLWriter $xmlWriter
     */
    public function __construct(XMLWriter $xmlWriter)
    {
        $this->xmlWriter = $xmlWriter;
    }

    /**
     * Reset internal state
     * 
     * @return void
     */
    public function clear()
    {
        $this->xmlWriter->clear();
    }

    /**
     * Get created xml as a raw string
     *
     * @return string
     *
     * @todo Kolla upp vad output funktionen heter för XMLWriter..
     */
    public function getXML()
    {
        return $this->xmlWriter->getXml();
    }
}
