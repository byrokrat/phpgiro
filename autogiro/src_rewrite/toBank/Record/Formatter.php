<?php

namespace byrokrat\autogiro\toBank\Record;

/**
 * Format LegalPerson-objects for bgc file records
 */
interface Formatter
{
    /**
     * Formar person according to rule
     *
     * @param  \byrokrat\billing\LegalPerson $person
     * @return string
     */
    public function format(\byrokrat\billing\LegalPerson $person);
}
