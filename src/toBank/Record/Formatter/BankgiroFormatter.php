<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\autogiro\toBank\Record\Formatter;

use ledgr\autogiro\Exception\LogicException;

/**
 * Format bankgiro of creditor
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class BankgiroFormatter implements \ledgr\autogiro\toBank\Record\Formatter
{
    /**
     * Bankgiro account number right-aligned, zero-filled, 10 digits
     *
     * @param  LegalPerson $creditor
     * @return string
     * @throws LogicException If $creditor does not have a Bankgiro account
     */
    public function format(\ledgr\billing\LegalPerson $creditor)
    {
        $account = $creditor->getAccount();
        if (!$account instanceof \ledgr\banking\Bankgiro) {
            throw new LogicException("Creditor must have bankgiro account, found: {$account->getType()}");
        }

        // TODO use $account->format() once banking is at 2.0
        return str_pad(str_replace('-', '', $account->getNumber()), 10, '0', STR_PAD_LEFT);
    }
}
