<?php

namespace byrokrat\autogiro\toBank\Record;

/**
 * Record to reject online ag application
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
