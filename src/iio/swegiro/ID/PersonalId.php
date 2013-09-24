<?php
/**
 * This file is part of the swegiro package
 *
 * Copyright (c) 2012-13 Hannes Forsgård
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iio\swegiro\ID;

/**
 * Stb PersonalId extension
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class PersonalId extends \iio\stb\ID\PersonalId
{
    /**
     * Get personal id as payer number
     *
     * @return string
     */
    public function getPayerNr()
    {
        return $this->getDate()->format('ymd')
            . $this->getIndividualNr()
            . $this->getCheckDigit();
    }

    /**
     * Get id without delimiter
     *
     * Year represented using four digits
     *
     * @return string
     */
    public function getFullIdNoDelimiter()
    {
        return $this->getDate()->format('Ymd')
            . $this->getIndividualNr()
            . $this->getCheckDigit();
    }
}
