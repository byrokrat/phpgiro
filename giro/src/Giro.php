<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\giro;

use DOMDocument;
use ledgr\giro\Exception\ValidatorException;

/**
 * Create and parse files for swedish giro systems
 *
 * Giro is the application frontend. Inject a concrete factory for the concrete
 * giro system. See readme for examples.
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class Giro
{
    /**
     * @var FactoryInterface Concrete factory
     */
    private $factory;

    /**
     * Constructor
     *
     * @param FactoryInterface $factory Concrete factory
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
