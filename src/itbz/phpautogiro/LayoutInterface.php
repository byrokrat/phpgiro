<?php
/**
 * This file is part of the swegiro package
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
    const LAYOUT_ABC = 1;

    /**
     * Layout D constant
     */
    const LAYOUT_D = 2;

    /**
     * Layout E constant
     */
    const LAYOUT_E = 3;

    /**
     * Layout F constant
     */
    const LAYOUT_F = 4;

    /**
     * Layout G constant
     */
    const LAYOUT_G = 5;

    /**
     * Layout H constant
     */
    const LAYOUT_H = 6;

    /**
     * Layout I constant
     */
    const LAYOUT_I = 7;

    /**
     * Layout J constant
     */
    const LAYOUT_J = 8;

    /**
     * Layout BGMAX constant
     */
    const LAYOUT_BGMAX = 9;

    /**
     * Layout PRIV_D constant
     */
    const LAYOUT_OLD_D = 10;

    /**
     * Layout PRIV_E constant
     */
    const LAYOUT_OLD_E = 11;

    /**
     * Layout PRIV_F constant
     */
    const LAYOUT_OLD_F = 12;

    /**
     * Layout PRIV_G constant
     */
    const LAYOUT_OLD_G = 13;
}
