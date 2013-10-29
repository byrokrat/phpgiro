<?php
/**
 * This file is part of the autogiro package
 *
 * Copyright (c) 2012-13 Hannes Forsgård
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iio\autogiro;

/**
 * Layout to strategy identifiers
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
interface Layouts
{
    /**
     * Autogiro layout ABC
     */
    const LAYOUT_AG_ABC = 'iio\autogiro\Strategy\LayoutABC';

    /**
     * Autogiro layout D
     */
    const LAYOUT_AG_D = 'iio\autogiro\Strategy\LayoutD';

    /**
     * Autogiro layout E
     */
    const LAYOUT_AG_E = 'iio\autogiro\Strategy\LayoutE';

    /**
     * Autogiro layout F
     */
    const LAYOUT_AG_F = 'iio\autogiro\Strategy\LayoutF';

    /**
     * Autogiro layout G
     */
    const LAYOUT_AG_G = 'iio\autogiro\Strategy\LayoutG';

    /**
     * Autogiro layout H
     */
    const LAYOUT_AG_H = 'iio\autogiro\Strategy\LayoutH';

    /**
     * Autogiro layout I
     */
    const LAYOUT_AG_I = 'iio\autogiro\Strategy\LayoutI';

    /**
     * Autogiro layout J
     */
    const LAYOUT_AG_J = 'iio\autogiro\Strategy\LayoutJ';

    /**
     * Autogiro layout BGMAX
     */
    const LAYOUT_AG_BGMAX = 9;

    /**
     * Autogiro layout PRIV_D
     */
    const LAYOUT_AG_OLD_D = 10;

    /**
     * Autogiro layout PRIV_E
     */
    const LAYOUT_AG_OLD_E = 11;

    /**
     * Autogiro layout PRIV_F
     */
    const LAYOUT_AG_OLD_F = 12;

    /**
     * Autogiro layout PRIV_G
     */
    const LAYOUT_AG_OLD_G = 13;
}
