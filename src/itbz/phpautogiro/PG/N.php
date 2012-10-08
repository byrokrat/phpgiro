<?php
/**
 * This file is part of the STB package
 *
 * Copyright (c) 2011-12 Hannes Forsgård
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Hannes Forsgård <hannes.forsgard@gmail.com>
 * @package itbz\phpautogiro\PG
 */

namespace itbz\phpautogiro\PG;

use itbz\phpautogiro\Char80;

/**
 * PlusGiro file layout N
 *
 * @package itbz\phpautogiro\PG
 */
class N extends Char80
{

    /**
     * Layout names
     *
     * @var array
     */
    protected $layoutNames = array(
        'N' => "INBETALNINGSSERVICE - Sammanställning av Inkommande betalningar",
    );

    /**
     * Nr of transaction posts in file
     *
     * @var int
     */
    private $postCount = 0;

    /**
     * File transaction post sum
     *
     * @var float
     */
    private $postSum = 0;


    /**
     * Layout id
     *
     * @var string
     */
    protected $layout = 'N';


    /**
     * Regex represention a valid file structure
     *
     * @var string
     */
    protected $struct = "/^(0010(2030(40)+50)+90)+$/";


    /**
     * Map transaction codes (TC) to line-parsing regexp and receiving method
     *
     * @var array
     */
    protected $map = array(
        '00' => array("/^00(\d{6})(.{33})IS (.{4})N(\d{6}).{10}(J| )\s*$/", 'parseHead'),
        '10' => array("/^10(\d{6})(.{32})\s*$/", 'parseCustomer'),
        '20' => array("/^20(\d{6})(.{10})\s*$/", 'parseIS'),
        '30' => array("/^30(\d{6})(.{10})(\d{6})\s*$/", 'parseDate'),
        '40' => array("/^40(.{25})(\d{13}).{7}(\d)(\d{10})(\d{8})(J| )\s*$/", 'parseTransaction'),
        '50' => array("/^50(\d{6})(.{10})(\d{6})(\d{7})(\d{15})\s*$/", "parseISfoot"),
        '90' => array("/^90(\d{6}).{10}(\d{6})(\d{7})(\d{15})\s*$/", "parseFoot"),
    );


    /**
     * Parse head
     *
     * @param string $agencyNr
     *
     * @param string $agencyName
     *
     * @param string $accountingUnit
     *
     * @param string $date
     *
     * @param string $reject
     *
     * @return bool TRUE if succesfull, FALSE on failure
     */
    protected function parseHead($agencyNr, $agencyName, $accountingUnit, $date, $reject)
    {
        $this->clearValues();
        $this->setValue('agency', $agencyNr, TRUE);
        $this->setValue('agencyName', trim(utf8_encode($agencyName)), TRUE);
        $this->setValue('accountingUnit', $accountingUnit, TRUE);
        $this->setValue('date', $date, TRUE);
        $this->setValue('rejectReg', $reject, TRUE);

        return TRUE;
    }


    /**
     * Parse customer
     *
     * @param string $customerNr
     *
     * @param string $customer
     *
     * @return bool TRUE if succesfull, FALSE on failure
     */
    protected function parseCustomer($customerNr, $customer)
    {
        if ( !$this->setValue('customer', trim(utf8_decode($customer)), TRUE) ) {
            $this->error(_("Unvalid customer name"));

            return FALSE;
        }

        return $this->setCustAndIs($customerNr);
    }


    /**
     * Parse IS
     *
     * @param string $customerNr
     *
     * @param string $ISnr
     *
     * @return bool TRUE if succesfull, FALSE on failure
     */
    protected function parseIS($customerNr, $ISnr)
    {
        if (!$this->setValue('account', trim($ISnr))) {
            $this->error(_("Unvalid account"));

            return FALSE;
        }

        return $this->setCustAndIs($customerNr);
    }


    /**
     * Parse date
     *
     * @param string $customerNr
     *
     * @param string $ISnr
     *
     * @param string $date
     *
     * @return bool TRUE if succesfull, FALSE on failure
     */
    protected function parseDate($customerNr, $ISnr, $date)
    {
        if (!$this->setValue('transactionDate', $date)) {
            $this->error(_("Unvalid date"));

            return FALSE;
        }

        return $this->setCustAndIs($customerNr, $ISnr);
    }


    /**
     * Parse transaction
     *
     * @param string $ref
     *
     * @param string $amount
     *
     * @param string $senderCode
     *
     * @param string $sender
     *
     * @param string $nr
     *
     * @param string $reject
     *
     * @return bool TRUE if succesfull, FALSE on failure
     */
    protected function parseTransaction($ref, $amount, $senderCode, $sender, $nr, $reject)
    {
        $t = array(
            "ref" => trim(utf8_encode($ref)),
            "amount" => $this->str2amount($amount),
            "date" => $this->getValue('transactionDate'),
            "sender" => $sender,
            "senderCode" => $senderCode,
            "idNr" => $nr,
            "reject" => $reject,
        );
        $this->push($t);

        return TRUE;
    }


    /**
     * Parse IS foot
     *
     * @param string $customerNr
     *
     * @param string $ISnr
     *
     * @param string $date
     *
     * @param string $nrTrans
     *
     * @param string $sumTrans
     *
     * @return bool TRUE if succesfull, FALSE on failure
     */
    protected function parseISfoot($customerNr, $ISnr, $date, $nrTrans, $sumTrans)
    {
        if (!$this->setCustAndIs($customerNr, $ISnr)) return FALSE;

        if (!$this->setValue('date', $date)) {
            $this->error(_("Unvalid date"));
            return FALSE;
        }

        $this->postCount += $this->count();
        $this->postSum += $this->sum('amount');

        if ((int)$nrTrans != $this->count()) {
            $this->error(_("Unvalid file content, wrong number of transaction posts."));
            return FALSE;
        }

        if ($this->str2amount($sumTrans) != $this->sum('amount')) {
            $this->error(_("Unvalid file content, wrong transaction sum."));
            return FALSE;
        }

        $this->writeSection();

        return TRUE;
    }


    /**
     * Parse foot
     *
     * @param string $agencyNr
     *
     * @param string $date
     *
     * @param string $nrTrans
     *
     * @param string $sumTrans
     *
     * @return bool TRUE if succesfull, FALSE on failure
     */
    protected function parseFoot($agencyNr, $date, $nrTrans, $sumTrans)
    {
        if (!$this->setValue('agency', $agencyNr)) {
            $this->error(_("Agency number does not match."));
            return FALSE;
        }
        if (!$this->setValue('date', $date)) {
            $this->error(_("Date does not match."));
            return FALSE;
        }

        if ((int)$nrTrans != $this->postCount) {
            $this->error(_("Unvalid file content, wrong number of transaction posts."));
            return FALSE;
        }

        if ($this->str2amount($sumTrans) != $this->postSum) {
            $this->error(_("Unvalid file content, wrong transaction sum."));
            return FALSE;
        }
        
        return TRUE;
    }


    /**
     * Set customer number and is number
     *
     * @param string $customerNr
     *
     * @param string $ISnr
     *
     * @return bool TRUE on success, FALSE if an error occured
     */
    private function setCustAndIs($customerNr, $ISnr = FALSE)
    {
        if ( !$this->setValue('customerNr', $customerNr, TRUE) ) {
            $this->error(_("Unvalid customer number"));
            return FALSE;
        }
        if ( $ISnr && !$this->setValue('account', trim($ISnr)) ) {
            $this->error(_("Unvalid account"));
            return FALSE;
        }
        return TRUE;
    }

}
