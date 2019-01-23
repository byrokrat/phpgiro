<?php

namespace byrokrat\autogiro\toBank\Record;

/**
 * Record to add mandate to bgc store
 */
class RegisterMandateRecord extends MandateRecord
{
    /**
     * Get record as string
     *
     * @return string
     */
    public function getRecord()
    {
        return '04'
            . $this->formatters->getBankgiroFormatter()->format($this->creditor)
            . $this->formatters->getPayerNumberFormatter()->format($this->debtor)
            . $this->debtor->getAccount()->to16()
            . $this->formatters->getIdFormatter()->format($this->debtor)
            . str_repeat(' ', 24);
    }
}
