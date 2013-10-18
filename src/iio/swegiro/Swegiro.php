<?php
/**
 * This file is part of the swegiro package
 *
 * Copyright (c) 2012-13 Hannes Forsgård
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iio\swegiro;

use DOMDocument;
use iio\swegiro\Factory\FactoryInterface;
use iio\swegiro\Exception\ValidatorException;

/**
 * Create and parse files for swedish giro systems
 *
 * Swegiro is the application frontend. Inject concrete factory for the selected
 * giro system. See readme for examples.
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class Swegiro
{
    /**
     * @var FactoryInterface Concrete factory
     */
    private $factory;

    /**
     * Constructor
     *
     * @param FactoryInterface $factory Concrete factory for giro system
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Sniff layout type of file
     *
     * @param  array  $lines Giro file contents
     * @return scalar Layout identifier returned by sniffer
     */
    public function sniffGiroType(array $lines)
    {
        return $this->factory->createSniffer()->sniffGiroType(
            $this->trimLines($lines)
        );
    }

    /**
     * Validate XML content
     *
     * @param  DOMDocument        $doc
     * @param  scalar             $giroType Layout identifier
     * @return DOMDocument        The valid document
     * @throws ValidatorException If validation fails
     */
    public function validateXML(DOMDocument $doc, $giroType = '')
    {
        $validator = $this->factory->createValidator($giroType);
        if (!$validator->isValid($doc)) {
            throw new ValidatorException($validator->getError());
        }

        return $doc;
    }

    /**
     * Convert giro files to XML
     * 
     * @param  array       $lines Giro file contents
     * @return DOMDocument
     */
    public function convertToXML(array $lines)
    {
        $lines = $this->trimLines($lines);
        $giroType = $this->sniffGiroType($lines);

        return $this->validateXML(
            $this->factory->createParser($giroType)->parse($lines),
            $giroType
        );
    }

    /**
     * Remove empty lines from array
     *
     * @param  array $lines Giro file contents
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
}
