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
 * @package itbz\swegiro
 */

namespace itbz\swegiro;

use DOMDocument;
use itbz\swegiro\Parser\Parser;

/**
 * Create and parse files for swedish giro systems
 *
 * @package itbz\swegiro
 */
class Swegiro
{
    /**
     * Concrete factory
     *
     * @var FactoryInterface
     */
    private $factory;

    /**
     * Create and parse files for swedish giro systems
     *
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Convert giro files to XML
     * 
     * @param array $lines Giro file contents
     * 
     * @return DOMDocument
     */
    public function convertToXML(array $lines)
    {
        // Remove empty lines
        $lines = array_filter(
            $lines,
            function ($line) {
                return !!trim($line);
            }
        );

        $sniffer = $this->factory->createSniffer();
        $layoutFlag = $sniffer->sniff($lines);

        $parser = new Parser(
            $this->factory->createParserStrategy($layoutFlag),
            $this->factory->createValidator($layoutFlag)
        );

        return $parser->parse($lines);
    }
}
