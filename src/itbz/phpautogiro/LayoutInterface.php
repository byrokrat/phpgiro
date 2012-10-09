<?php
/**
 * This file is part of the STB package
 *
 * Copyright (c) 2012 Hannes Forsgård
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Hannes Forsgård <hannes.forsgard@gmail.com>
 * @package itbz\phpautogiro
 */

namespace itbz\phpautogiro;

/**
 * Sniff the layout type of a AG-string
 *
 * @package itbz\phpautogiro
 */
interface LayoutInterface
{
    /**
     * Layout D constant
     */
    const LAYOUT_AGP_D = 1;

    /**
     * Layout E constant
     */
    const LAYOUT_AGP_E = 3;

    /**
     * Layout F constant
     */
    const LAYOUT_AGP_F = 5;

    /**
     * Layout G constant
     */
    const LAYOUT_AGP_G = 7;

    /**
     * Layout H constant
     */
    const LAYOUT_AGP_H = 9;



    /**
     * Layout D constant
     */
    const LAYOUT_NEW_D = 2;

    /**
     * Layout E-new constant
     */
    const LAYOUT_NEW_E = 4;

    /**
     * Layout F-new constant
     */
    const LAYOUT_NEW_F = 6;

    /**
     * Layout G-new constant
     */
    const LAYOUT_NEW_G = 8;

    /**
     * Layout I constant
     */
    const LAYOUT_NEW_I = 10;
}
