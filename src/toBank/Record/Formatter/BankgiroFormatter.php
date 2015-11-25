<?php

namespace byrokrat\autogiro\toBank\Record\Formatter;

use byrokrat\billing\LegalPerson;
use byrokrat\autogiro\Exception\LogicException;

/**
 * Format bankgiro of creditor
 */
class BankgiroFormatter implements \byrokrat\autogiro\toBank\Record\Formatter
{
    /**
     * Bankgiro account number right-aligned, zero-filled, 10 digits
     *
     * @param  LegalPerson $creditor
     * @return string
     * @throws LogicException If $creditor does not have a Bankgiro account
     */
    public function format(LegalPerson $creditor)
    {
        $account = $creditor->getAccount();
        if (!$account instanceof \byrokrat\banking\Bankgiro) {
            throw new LogicException("Creditor must have bankgiro account, found: {$account->getType()}");
        }

        // TODO use $account->format() once banking is at 2.0
        return str_pad(str_replace('-', '', $account->getNumber()), 10, '0', STR_PAD_LEFT);
    }
}
