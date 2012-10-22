<?php
namespace itbz\swegiro\PG;

// INBETALNINGSSERVICE - Sammanställning av Inkommande betalningar
class N extends itbz\swegiro\Char80
{
    protected $struct = "/^(0010(2030(40)+50)+90)+$/";

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

    protected function parseHead($agencyNr, $agencyName, $accountingUnit, $date, $reject)
    {
        $this->clearValues();
        $this->setValue('agency', $agencyNr, true);
        $this->setValue('agencyName', trim(utf8_encode($agencyName)), true);
        $this->setValue('accountingUnit', $accountingUnit, true);
        $this->setValue('date', $date, true);
        $this->setValue('rejectReg', $reject, true);

        return true;
    }

    protected function parseCustomer($customerNr, $customer)
    {
        if ( !$this->setValue('customer', trim(utf8_decode($customer)), true) ) {
            $this->error(_("Unvalid customer name"));

            return false;
        }

        return $this->setCustAndIs($customerNr);
    }

    protected function parseIS($customerNr, $ISnr)
    {
        if (!$this->setValue('account', trim($ISnr))) {
            $this->error(_("Unvalid account"));

            return false;
        }

        return $this->setCustAndIs($customerNr);
    }

    protected function parseDate($customerNr, $ISnr, $date)
    {
        if (!$this->setValue('transactionDate', $date)) {
            $this->error(_("Unvalid date"));

            return false;
        }

        return $this->setCustAndIs($customerNr, $ISnr);
    }

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

        return true;
    }

    protected function parseISfoot($customerNr, $ISnr, $date, $nrTrans, $sumTrans)
    {
        if (!$this->setCustAndIs($customerNr, $ISnr)) return false;

        if (!$this->setValue('date', $date)) {
            $this->error(_("Unvalid date"));
            return false;
        }

        $this->postCount += $this->count();
        $this->postSum += $this->sum('amount');

        if ((int)$nrTrans != $this->count()) {
            $this->error(_("Unvalid file content, wrong number of transaction posts."));
            return false;
        }

        if ($this->str2amount($sumTrans) != $this->sum('amount')) {
            $this->error(_("Unvalid file content, wrong transaction sum."));
            return false;
        }

        $this->writeSection();

        return true;
    }

    protected function parseFoot($agencyNr, $date, $nrTrans, $sumTrans)
    {
        if (!$this->setValue('agency', $agencyNr)) {
            $this->error(_("Agency number does not match."));
            return false;
        }
        if (!$this->setValue('date', $date)) {
            $this->error(_("Date does not match."));
            return false;
        }

        if ((int)$nrTrans != $this->postCount) {
            $this->error(_("Unvalid file content, wrong number of transaction posts."));
            return false;
        }

        if ($this->str2amount($sumTrans) != $this->postSum) {
            $this->error(_("Unvalid file content, wrong transaction sum."));
            return false;
        }
        
        return true;
    }

    /**
     * Set customer number and is number
     */
    private function setCustAndIs($customerNr, $ISnr = false)
    {
        if ( !$this->setValue('customerNr', $customerNr, true) ) {
            $this->error(_("Unvalid customer number"));
            return false;
        }
        if ( $ISnr && !$this->setValue('account', trim($ISnr)) ) {
            $this->error(_("Unvalid account"));
            return false;
        }
        return true;
    }
}
