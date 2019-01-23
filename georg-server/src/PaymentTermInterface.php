<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\georg;

/**
 * Class constants used when working woth payment terms
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
interface PaymentTermInterface
{
    /**
     * Payment term indicating that a response from the bank is required
     */
    const TERM_WAITING = 1;

    /**
     * Payment term indicating active autogiro
     */
    const TERM_AG = 2;

    /**
     * Payment term indicating that consent should sent to the bank
     */
    const TERM_BAG = 4;

    /**
     * Payment term indicating that consent should we withdrawn
     */
    const TERM_MAG = 8;

    // TODO behöver jag även TERM_IS_PERIODIC?

    /**
     * Payment term indicating periodic billing should start
     */
    const TERM_START_PERIODIC = 16;

    /**
     * Payment term indicating periodic billing should stop
     */
    const TERM_STOP_PERIODIC = 32;
}
