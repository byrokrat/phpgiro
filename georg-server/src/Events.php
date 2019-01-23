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
 * List of georg events
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class Events
{
    /**
     * Event type thrown when a consent request is sent to the bank.
     *
     * The event listener receives an DonorEvent instance.
     */
    const REQUEST_CONSENT = 'donor.request_consent';

    /**
     * Event type thrown when a consent is approved by the bank
     *
     * The event listener receives an DonorEvent instance.
     */
    const APPROVED_CONSENT = 'donor.approved_consent';

    /**
     * Event type thrown when a donor is billed
     *
     * The event listener receives an DonorEvent instance.
     */
    const BILL_ONCE = 'donor.bill_once';
}
