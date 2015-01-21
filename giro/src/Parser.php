<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\giro;

use ledgr\giro\Exception\ParserException;
use DOMDocument;

/**
 * Parse AG files using designated strategy
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class Parser
{
    /**
     * @var StrategyInterface Parsing strategy
     */
    private $strategy;

    /**
     * @var array Parsing regular expressions maped to parser methods
     */
    private $regexpMap;

    /**
     * Parse AG files using designated strategy
     *
     * @param StrategyInterface $strategy
     */
    public function __construct(StrategyInterface $strategy)
    {
        $this->strategy = $strategy;
        $this->regexpMap = $strategy->getRegexpMap();
    }

    /**
     * Parse AG files using designated strategy
     * 
     * @param  array       $lines AG file contents
     * @return DOMDocument
     */
    public function parse(array $lines)
    {
        $this->strategy->clear();

        foreach ($lines as $line) {
            $this->parseLine($line);
        }

        return $this->createDomDocument($this->strategy->getXML());
    }

    /**
     * Parse one line
     *
     * @param  string          $line
     * @return void
     * @throws ParserException If unable to parse line
     */
    public function parseLine($line)
    {
        foreach ($this->regexpMap as $regexp => $fnName) {
            if (preg_match($regexp, $line, $matches)) {
                array_shift($matches);
                call_user_func(
                    array($this->strategy, $fnName),
                    array_map('utf8_encode', $matches)
                );

                return;
            }
        }

        throw new ParserException("Unknown line '$line'");
    }

    /**
     * Create DOMDocument from raw xml
     *
     * @param  string          $xml
     * @return DOMDocument
     * @throws ParserException If generated XML is not valid
     */
    public function createDomDocument($xml)
    {
        assert('is_string($xml)');

        $domDocument = new DOMDocument;
        if (!@$domDocument->loadXML($xml)) {
            $libxmlError = libxml_get_last_error();
            $msg = "Parsing empty file?";
            if ($libxmlError) {
                $msg = $libxmlError->message;
            }
            throw new ParserException("XML validation error: $msg");
        }

        return $domDocument;
    }
}
