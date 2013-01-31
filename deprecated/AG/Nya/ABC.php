<?php
namespace iio\swegiro\AG\Nya;

/**
 * Added functinality for creating A, B or C files
 * 
 * This class is not needed for parsing new A, B or C files.
 */
class ABC extends \iio\swegiro\AG\ABC
{
    /**
     * Write consent post for BG accout to file. When dealing with BG accounts
     * $betNr is always the same as the account number.
     * @param string $bgFrom BG account nr, and number to identify consent.
     * @param bool $reject Set to true if this is an answer to an online application
     * and you DECLINE the application.
     */
    public function addBgConsent($bgFrom, $reject=false)
    {
        $bgFrom = str_pad($bgFrom, 16, '0', STR_PAD_LEFT);
        $blank = str_pad("", 48);
        $reject = ($reject) ? "AV" : "  ";
        $bgTo = $this->getValue('bg');

        // addLine() är borttagen, ska skapa XML istället
        $this->addLine("04$bgTo$bgFrom$blank$reject");
    }

    /**
     * Write change betNr post to file.
     * @param string $oldBetNr Number to identify AG consent. Max 16 numbers.
     * @param string $newBetNr Number to identify AG consent. Max 16 numbers.
     */
    public function changeBetNr($oldBetNr, $newBetNr)
    {
        $oldBetNr = str_pad($oldBetNr, 16, '0', STR_PAD_LEFT);
        $newBetNr = str_pad($newBetNr, 16, '0', STR_PAD_LEFT);
        $bg = $this->getValue('bg');

        // addLine() är borttagen, ska skapa XML istället
        $this->addLine("05$bg$oldBetNr$bg$newBetNr");
    }
}
