<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\autogiro\toBank;

/**
 * Container for transactions
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class TransactionContainer extends WritingContainer
{
    /**
     * Get contained transactions and assignments in the bank format
     *
     * @return string ISO-8859-1 encoded string
     */
    public function createBankData()
    {
        // TODO implement
    }
}
