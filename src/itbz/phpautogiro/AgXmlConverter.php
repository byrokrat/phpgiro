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
 * @package itbz\phpautogiro
 */

namespace itbz\phpautogiro;

use DOMDocument;

/**
 * Convert AG files to XML
 *
 * @package itbz\phpautogiro
 */
class AgXmlConverter
{
    /**
     * Sniff AG file type
     *
     * @var Sniffer
     */
    private $sniffer;

    /**
     * Factory for creating parsers for AG file types
     *
     * @var ParserFactory
     */
    private $parserFactory;

    /**
     * Convert AG files to XML
     *
     * @param Sniffer $sniffer
     * @param ParserFactory $parserFactory
     */
    public function __construct(Sniffer $sniffer, ParserFactory $parserFactory)
    {
        $this->sniffer = $sniffer;
        $this->parserFactory = $parserFactory;
    }

    /**
     * Convert AG files to XML
     * 
     * @param array $lines AG file contents
     * 
     * @return DOMDocument
     */
    public function convert(array $lines)
    {
        // Remove empty lines
        $lines = array_filter(
            $lines,
            function ($line) {
                return !!trim($line);
            }
        );

        return $this->parserFactory
            ->build($this->sniffer->sniff($lines))
            ->parse($lines);
    }
}
