<?php
/**
 * This file is part of the swegiro package
 *
 * Copyright (c) 2012-13 Hannes Forsgård
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iio\swegiro\Validator;

use iio\swegiro\ValidatorInterface;
use DOMImplementation;
use DOMDocumentType;
use DOMDocument;

/**
 * Validate DOMDocuments using DTDs
 *
 * @author  Hannes Forsgård <hannes.forsgard@gmail.com>
 * @package swegiro
 */
class DtdValidator implements ValidatorInterface
{
    /**
     * @var string Last error message
     */
    private $error = '';

    /**
     * @var string XML qualified name
     */
    private $rootName;

    /**
     * @var DOMImplementation DOM creator resource
     */
    private $creator;

    /**
     * Constructor
     *
     * @param string $rootName Name of the document root node
     * @param string $dtd
     */
    public function __construct($rootName, $dtd)
    {
        assert('is_string($rootName) && !empty($rootName)');
        assert('is_string($dtd)');

        $this->rootName = $rootName;

        $this->creator = new DOMImplementation;
        $this->doctype = $this->creator->createDocumentType(
            $this->rootName,
            null,
            'data://text/plain;base64,'.base64_encode($dtd)
        );
    }

    /**
     * {@inheritdoc}
     *
     * @param  DOMDocument $doc Document to validate
     * @return boolean     True if document is valid, false otherwise
     */
    public function isValid(DOMDocument $doc)
    {
        $newDocument = $this->creator->createDocument(null, null, $this->doctype);
        $newDocument->encoding = $doc->encoding ? $doc->encoding : 'utf-8';

        $rootNode = $doc->getElementsByTagName($this->rootName)->item(0);

        if (!$rootNode) {
            $this->error = _('Unable to extract root node from document');

            return false;
        }

        $newDocument->appendChild(
            $newDocument->importNode(
                $rootNode,
                true
            )
        );

        if (@$newDocument->validate()) {
            $this->error = '';

            return true;
        } else {
            $libxmlError = libxml_get_last_error();
            $this->error = $libxmlError->message;

            return false;
        }
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }
}
