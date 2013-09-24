<?php
/**
 * This file is part of the swegiro package
 *
 * Copyright (c) 2012-13 Hannes Forsgård
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iio\swegiro;

/**
 * Sniff the layout type of a AG-string
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
interface LayoutInterface
{
    /**
     * Layout ABC constant
     */
    const LAYOUT_AG_ABC = 1;

    /**
     * Layout D constant
     */
    const LAYOUT_AG_D = 2;

    /**
     * Layout E constant
     */
    const LAYOUT_AG_E = 3;

    /**
     * Layout F constant
     */
    const LAYOUT_AG_F = 4;

    /**
     * Layout G constant
     */
    const LAYOUT_AG_G = 5;

    /**
     * Layout H constant
     */
    const LAYOUT_AG_H = 6;

    /**
     * Layout I constant
     */
    const LAYOUT_AG_I = 7;

    /**
     * Layout J constant
     */
    const LAYOUT_AG_J = 8;

    /**
     * Layout BGMAX constant
     */
    const LAYOUT_AG_BGMAX = 9;

    /**
     * Layout PRIV_D constant
     */
    const LAYOUT_AG_OLD_D = 10;

    /**
     * Layout PRIV_E constant
     */
    const LAYOUT_AG_OLD_E = 11;

    /**
     * Layout PRIV_F constant
     */
    const LAYOUT_AG_OLD_F = 12;

    /**
     * Layout PRIV_G constant
     */
    const LAYOUT_AG_OLD_G = 13;
}
