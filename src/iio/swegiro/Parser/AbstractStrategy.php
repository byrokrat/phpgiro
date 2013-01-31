<?php
/**
 * This file is part of the swegiro package
 *
 * Copyright (c) 2012-13 Hannes Forsgård
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iio\swegiro\Parser;

use iio\Exception\ParserException;
use iio\swegiro\XMLWriter;

/**
 * Abstract parser strategy class
 *
 * @author  Hannes Forsgård <hannes.forsgard@gmail.com>
 * @package swegiro
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
     */
    public function getXML()
    {
        return $this->xmlWriter->getXml();
    }
}
