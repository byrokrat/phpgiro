<?php
/**
 * This file is part of the STB package
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
 * Parse AG files using designated strategy
 *
 * @package itbz\phpautogiro
 */
class Parser
{
    /**
     * Parsing strategy
     *
     * @var ParserStrategyInterface
     */
    private $strategy;

    /**
     * Parse AG files using designated strategy
     *
     * @param ParserStrategyInterface $strategy
     */
    public function __construct(ParserStrategyInterface $strategy)
    {
        $this->strategy = $strategy;
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
            $this->strategy->parse($line);
        }

        $xml = $this->strategy->getDomDocument();

        // create DomDocument
        // Validate against DTD

        return $xml;
    }
}
