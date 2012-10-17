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
 * AG layout G, feedback on deletions and changes. New File layout.
 *
 * @package itbz\swegiro\AG\Nya
 */
class G extends \itbz\swegiro\AG\G
{
    
    /**
     * AG layout G, feedback on deletions and changes. New File layout.
     *
     * @param string $customerNr
     *
     * @param string $bg
     */
    public function __construct($customerNr = false, $bg = false)
    {
        parent::__construct($customerNr, $bg);
        $this->map['01'] = array(utf8_decode("/^01AUTOGIRO.{12}..(\d{8}).{12}MAKULERING\/ÄNDRING..(\d{6})(\d{10})/"), 'parseHeadDateCustBg');
    }

}
