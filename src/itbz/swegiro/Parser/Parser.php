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
use itbz\swegiro\Exception\ValidatorException;
use itbz\swegiro\ValidatorInterface;
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
     * @var StrategyInterface
     */
    private $strategy;

    /**
     * DomDocument validator object
     *
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * Parse AG files using designated strategy
     *
     * @param StrategyInterface $strategy
     * @param ValidatorInterface $validator
     */
    public function __construct(
        StrategyInterface $strategy,
        ValidatorInterface $validator
    ) {
        $this->strategy = $strategy;
        $this->validator = $validator;
    }

    /**
     * Get parsing strategy
     *
     * @return StrategyInterface
     */
    public function getStrategy()
    {
        return $this->strategy;
    }

    /**
     * Parse AG files using designated strategy
     * 
     * @param array $lines AG file contents
     * 
     * @return DOMDocument
     *
     * @throws ParserException If generated XML is not valid
     * @throws ValidatorException If validation fails
     */
    public function parse(array $lines)
    {
        $this->strategy->clear();

        foreach ($lines as $line) {
            $this->strategy->parseLine($line);
        }

        $xml = $this->strategy->getXml();

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

        if (!$this->validator->isValid($domDocument)) {
            throw new ValidatorException($this->validator->getError());
        }

        return $domDocument;
    }
}
