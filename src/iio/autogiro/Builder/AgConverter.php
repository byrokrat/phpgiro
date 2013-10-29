<?php
/**
 * This file is part of the autogiro package
 *
 * Copyright (c) 2012-13 Hannes Forsgård
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iio\autogiro\Builder;

use iio\stb\ID\PersonalId;
use iio\stb\Banking\Bankgiro;

/**
 * Convert PersonalId and Bankgiro to autogiro formats
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class AgConverter
{
    /**
     * Convert personal id to payer number
     *
     * @param  PersonalId $id
     * @return string
     */
    public function convertPayerNr(PersonalId $id)
    {
        return $id->getDate()->format('ymd')
            . $id->getIndividualNr()
            . $id->getCheckDigit();
    }

    /**
     * Convert personal id to ag format
     *
     * @param  PersonalId $id
     * @return string
     */
    public function convertId(PersonalId $id)
    {
        return $id->getDate()->format('Ymd')
            . $id->getIndividualNr()
            . $id->getCheckDigit();
    }

    /**
     * Convert bankgiro to ag format
     * 
     * @param  Bankgiro $bg
     * @return string
     */
    public function convertBankgiro(Bankgiro $bg)
    {
        return str_replace('-', '', $bg);
    }
}
