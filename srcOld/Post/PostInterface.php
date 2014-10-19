<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\autogiro\Post;

/**
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
interface PostInterface
{
    const HEADER = '01';
    const CONSENT_REVOKE = '03';
    const CONSENT_NEW = '04';
    const INVOICE_DEBIT = '82';
    const INVOICE_CREDIT = '32';

    /**
     * Get the raw string representation
     *
     * @return string
     */
    public function getRaw();
}
