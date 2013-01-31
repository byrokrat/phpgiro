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
use iio\swegiro\Parser\Parser;
use iio\swegiro\Exception\ValidatorException;

/**
 * Create and parse files for swedish giro systems
 *
 * Inject factory for the bank system under analysis. Se the Factory subpackage
 * for possible choices. Se README.md for examples.
 *
 * @author  Hannes Forsgård <hannes.forsgard@gmail.com>
 * @package swegiro
 */
class Swegiro
{
    /**
     * @var FactoryInterface Concrete factory
     */
    private $factory;

    /**
     * @var SnifferInterface Sniffer instance
     */
    private $sniffer;

    /**
     * @var ValidatorInterface Validator instance
     */
    private $validator;

    /**
     * Constructor
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

    /**
     * Check that XML is valid
     *
     * @param  DOMDocument        $doc
     * @return DOMDocument        The valid document
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
     * @param  array       $lines Giro file contents
     * @return DOMDocument
     */
    public function convertToXML(array $lines)
    {
        $lines = $this->trimLines($lines);

        $parser = $this->factory->createParser(
            $this->sniffer->sniffGiroType($lines)
        );

        return $this->validateXML(
            $parser->parse($lines)
        );
    }
}
