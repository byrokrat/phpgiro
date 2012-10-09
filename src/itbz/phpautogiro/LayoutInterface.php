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
     * Layout ABC constant
     */
    const LAYOUT_AGP_ABC = 1;

    /**
     * Layout D constant
     */
    const LAYOUT_AGP_D = 2;

    /**
     * Layout E constant
     */
    const LAYOUT_AGP_E = 3;

    /**
     * Layout F constant
     */
    const LAYOUT_AGP_F = 4;

    /**
     * Layout G constant
     */
    const LAYOUT_AGP_G = 5;

    /**
     * Layout H constant
     */
    const LAYOUT_AGP_H = 6;

    /**
     * Layout D constant
     */
    const LAYOUT_NEW_D = 7;

    /**
     * Layout E-new constant
     */
    const LAYOUT_NEW_E = 8;

    /**
     * Layout F-new constant
     */
    const LAYOUT_NEW_F = 9;

    /**
     * Layout G-new constant
     */
    const LAYOUT_NEW_G = 10;

    /**
     * Layout H-new constant
     */
    const LAYOUT_NEW_H = 11;

    /**
     * Layout I constant
     */
    const LAYOUT_NEW_I = 12;

    /**
     * Layout J constant
     */
    const LAYOUT_NEW_J = 13;

    /**
     * Layout BGMAX constant
     */
    const LAYOUT_BGMAX = 14;

    /**
     * Layout ABC constant
     */
    const LAYOUT_NEW_ABC = 15;
}
