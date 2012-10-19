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
use itbz\swegiro\Exception\ValidatorException;

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
     * Sniffer instance
     *
     * @var SnifferInterface
     */
    private $sniffer;

    /**
     * Validator instance
     *
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * Create and parse files for swedish giro systems
     *
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
        $this->sniffer = $factory->createSniffer();
        $this->validator = $factory->createValidator();
    }

    /**
     * Remove empty lines from array
     *
     * @param array $lines Giro file contents
     *
     * @return array
     */
    public function trimLines(array $lines)
    {
        return array_filter(
            $lines,
            function ($line) {
                return !!trim($line);
            }
        );
    }

    /**
     * Check that XML is valid
     *
     * @param DOMDocument $doc
     *
     * @return DOMDocument The valid document
     *
     * @throws ValidatorException If validation fails
     */
    public function validateXML(DOMDocument $doc)
    {
        if (!$this->validator->isValid($doc)) {
            throw new ValidatorException($this->validator->getError());
        }

        return $doc;
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
        $lines = $this->trimLines($lines);

        $parser = $this->factory->createParser(
            $this->sniffer->sniff($lines)
        );

        return $this->validateXML(
            $parser->parse($lines)
        );
    }
}
