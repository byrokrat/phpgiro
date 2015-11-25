<?php

namespace byrokrat\autogiro\toBank;

/**
 * A record is represented as a line in the bgc file format
 */
interface Record
{
    /**
     * Get record as string
     *
     * @return string
     */
    public function getRecord();
}
