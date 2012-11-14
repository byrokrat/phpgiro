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

use itbz\swegiro\Exception\ParserException;
use DOMDocument;

/**
 * Parse AG files using designated strategy
 *
 * @package itbz\swegiro\Parser
 */
class Parser
{
    /**
     * Parsing strategy
     *
     * @var AbstractStrategy
     */
    private $strategy;

    /**
     * Parsing regular expressions maped to parser methods
     *
     * @var array
     */
    private $regexpMap;

    /**
     * Parse AG files using designated strategy
     *
     * @param AbstractStrategy $strategy
     */
    public function __construct(AbstractStrategy $strategy)
    {
        $this->strategy = $strategy;
        $this->regexpMap = $strategy->getRegexpMap();
    }

    /**
     * Parse AG files using designated strategy
     * 
     * @param array $lines AG file contents
     * 
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
     * @param string $line
     *
     * @return void
     *
     * @throws ParserException if unable to parse line
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

        $msg = sprintf(_("Unknown line '%s'"), $line);
        throw new ParserException($msg);
    }

    /**
     * Create DOMDocument from raw xml
     *
     * @param string $xml
     *
     * @return DOMDocument
     *
     * @throws ParserException If generated XML is not valid
     */
    public function createDomDocument($xml)
    {
        assert('is_string($xml)');

        $domDocument = new DOMDocument;
        if (!@$domDocument->loadXML($xml)) {
            $libxmlError = libxml_get_last_error();
            if ($libxmlError) {
                $msg = $libxmlError->message;
            } else {
                $msg = _("Parsing empty file?");
            }
            throw new ParserException(_('XML validation error: ') . $msg);
        }

        return $domDocument;
    }
}
