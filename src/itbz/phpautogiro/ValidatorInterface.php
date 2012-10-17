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
 * Interface for validating DOMDocuments
 *
 * @package itbz\phpautogiro
 */
interface ValidatorInterface
{
    /**
     * Check if document is valid
     *
     * @param DOMDocument $doc Document to validate
     *
     * @return boolean True if document is valid, false otherwise
     */
    public function isValid(DOMDocument $doc);

    /**
     * Get string describing the last validation error
     *
     * @return string
     */
    public function getError();
}
