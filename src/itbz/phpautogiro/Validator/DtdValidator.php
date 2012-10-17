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
 * @package itbz\phpautogiro\Validator
 */

namespace itbz\phpautogiro\Validator;

use itbz\phpautogiro\ValidatorInterface;
use DOMImplementation;
use DOMDocumentType;
use DOMDocument;

/**
 * Validate DOMDocuments using DTDs
 *
 * @package itbz\phpautogiro\Validator
 */
class DtdValidator implements ValidatorInterface
{
    /**
     * XML qualified name
     *
     * @var string
     */
    private $name;

    /**
     * DOM creator resource
     *
     * @var DOMImplementation
     */
    private $creator;

    /**
     * Validate DOMDocuments using DTDs
     *
     * @param string $name XML qualified name
     * @param string $dtd
     */
    public function __construct($name, $dtd)
    {
        assert('is_string($name)');
        assert('is_string($dtd)');

        $this->name = $name;

        $this->creator = new DOMImplementation;
        $this->doctype = $this->creator->createDocumentType(
            $this->name,
            null,
            'data://text/plain;base64,'.base64_encode($dtd)
        );
    }

    /**
     * {@inheritdoc}
     *
     * @param DOMDocument $xml Document to validate
     *
     * @return boolean True if document is valid, false otherwise
     */
    public function isValid(DOMDocument $xml)
    {
        $newDocument = $this->creator->createDocument(null, null, $this->doctype);
        $newDocument->encoding = $xml->encoding ? $xml->encoding : 'utf-8';

        $newDocument->appendChild(
            $newDocument->importNode(
                $xml->getElementsByTagName($this->name)->item(0),
                true
            );
        );

        return @$newDocument->validate();
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getError()
    {
        $libxmlError = libxml_get_last_error();
        if ($libxmlError) {

            return $libxmlError->message;
        } else {

            return '';
        }
    }
}
