<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\autogiro;

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
    const LAYOUT_AG_ABC = 'ledgr\autogiro\Strategy\LayoutABC';

    /**
     * Autogiro layout D
     */
    const LAYOUT_AG_D = 'ledgr\autogiro\Strategy\LayoutD';

    /**
     * Autogiro layout E
     */
    const LAYOUT_AG_E = 'ledgr\autogiro\Strategy\LayoutE';

    /**
     * Autogiro layout F
     */
    const LAYOUT_AG_F = 'ledgr\autogiro\Strategy\LayoutF';

    /**
     * Autogiro layout G
     */
    const LAYOUT_AG_G = 'ledgr\autogiro\Strategy\LayoutG';

    /**
     * Autogiro layout H
     */
    const LAYOUT_AG_H = 'ledgr\autogiro\Strategy\LayoutH';

    /**
     * Autogiro layout I
     */
    const LAYOUT_AG_I = 'ledgr\autogiro\Strategy\LayoutI';

    /**
     * Autogiro layout J
     */
    const LAYOUT_AG_J = 'ledgr\autogiro\Strategy\LayoutJ';

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
