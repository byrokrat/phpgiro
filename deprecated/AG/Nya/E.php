<?php
/**
 * This file is part of the STB package
 *
 * Copyright (c) 2011-12 Hannes Forsgård
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Hannes Forsgård <hannes.forsgard@gmail.com>
 * @package itbz\swegiro\AG\Nya
 */

namespace itbz\swegiro\AG\Nya;

/**
 * AG layout E, feedback on new and removed consents. New file layout.
 *
 * @package itbz\swegiro\AG\Nya
 */
class E extends \itbz\swegiro\AG\E
{

    /**
     * AG layout E, feedback on new and removed consents. New file layout.
     *
     * @param string $customerNr
     *
     * @param string $bg
     */
    public function __construct($customerNr = false, $bg = false)
    {
        parent::__construct($customerNr, $bg);
        $this->map['01'] = array("/^01AUTOGIRO.{12}..(\d{8}).{12}AG-MEDAVI.{11}(\d{6})(\d{10})\s*$/", 'parseHeadDateCustBg');
    }

}
