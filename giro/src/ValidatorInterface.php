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

/**
 * Interface for validating DOMDocuments
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
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
