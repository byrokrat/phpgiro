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
 * Record to remove mandate from bgc store
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class RemoveMandateRecord extends MandateRecord
{
    /**
     * Get record as string
     *
     * @return string
     */
    public function getRecord()
    {
        return '03'
            . $this->formatters->getBankgiroFormatter()->format($this->creditor)
            . $this->formatters->getPayerNumberFormatter()->format($this->debtor)
            . $this->debtor->getAccount()->to16()
            . str_repeat(' ', 36);
    }
}
