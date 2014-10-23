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
 * Record to reject online ag application
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class RejectMandateRecord extends RegisterMandateRecord
{
    /**
     * Get record as string
     *
     * @return string
     */
    public function getRecord()
    {
        return substr(parent::getRecord(), 0, 76) . 'AV  ';
    }
}
