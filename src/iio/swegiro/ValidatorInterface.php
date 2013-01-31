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

/**
 * Interface for validating DOMDocuments
 *
 * @author  Hannes Forsgård <hannes.forsgard@gmail.com>
 * @package swegiro
 */
interface ValidatorInterface
{
    /**
     * Check if document is valid
     *
     * @param  DOMDocument $doc Document to validate
     * @return boolean     True if document is valid, false otherwise
     */
    public function isValid(DOMDocument $doc);

    /**
     * Get string describing the last validation error
     *
     * @return string
     */
    public function getError();
}
