<?php

namespace byrokrat\autogiro\toBank\Record;

/**
 * Record to remove mandate from bgc store
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
