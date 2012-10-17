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
 * AG layout D, feedback on completed transactions. New file layout.
 *
 * @package itbz\swegiro\AG\Nya
 */
class D extends \itbz\swegiro\AG\D
{

    /**
     * AG layout D, feedback on completed transactions. New file layout.
     *
     * @param string $customerNr
     *
     * @param string $bg
     */
    public function __construct($customerNr = false, $bg = false)
    {
            echo "LAYOUT D new file type not supported..\n\n\n";
            return false;
    }

}
