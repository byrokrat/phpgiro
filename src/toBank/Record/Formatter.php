<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\autogiro\toBank\Record;

/**
 * Format LegalPerson-objects for bgc file records
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
interface Formatter
{
    /**
     * Formar person according to rule
     *
     * @param  \ledgr\billing\LegalPerson $person
     * @return string
     */
    public function format(\ledgr\billing\LegalPerson $person);
}
