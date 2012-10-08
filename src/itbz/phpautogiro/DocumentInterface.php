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

use \DOMDocument;

/**
 * Document interface
 *
 * @package itbz\phpautogiro
 */
interface DocumentInterface
{
    /**
     * Get DOMDocument object
     * 
     * @return DOMDocument
     */
    public function getDomDocument();

    /**
     * Check if document structure is valid
     * 
     * @return boolean
     */
    public function isValid();

    /**
     * Get document as raw xml string
     * 
     * @return string
     */
    public function getXml();
}
