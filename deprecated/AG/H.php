<?php
namespace itbz\swegiro\AG;

/**
 * Medgivanden via Internetbanken
 *
 * Produces stack items with the following:
 *
 * [betNr] =>
 * [account] => account nr, regular or BG
 * [orgNr/persNr] => swedish social security number, or arganizational number
 * [status] => status code
 * [statusMsg] => string message describing status
 * [info] => array of addition info
 * [address] => array
 */
class H extends Object
{
    protected $struct = "/^(51(52(5[3456])*)+59)+$/";

    protected $map = array(
        '51' => array("/^51(\d{8})9900(\d{10})AG-EMEDGIV/", 'parseHeadDateBg'),
        '52' => array("/^52(\d{10})(\d{16})(\d{4})(\d{12})(\d{12}).{5}(\d)/", 'parseNewConsent'),
        '53' => array("/^53(.{0,36})/", 'parseInfo'),
        '54' => array("/^54(.{0,36})(.{0,36})/", 'parseAddress'),
        '55' => array("/^55(.{0,36})(.{0,36})/", 'parseAddress'),
        '56' => array("/^56(.{0,5})(.{0,31})/", 'parseAddress'),
        '59' => array("/^59(\d{8})9900(\d{7})/", 'parseFoot'),
    );

    private $nrOfPosts = 0;

    protected $statusMsgs = array(
        0 => "Första meddelandet",
        1 => "Påminnelse nummer ett",
        2 => "Påminnelse nummer två",
    );

    protected function parseInfo($info){
        $this->nrOfPosts++;
        $info = trim(utf8_encode($info));
        $this->pushTo('info', $info);
        return true;
    }

    protected function parseAddress($addr1, $addr2 = false)
    {
        $this->nrOfPosts++;
        $addrs = func_get_args();
        foreach ( $addrs as $addr ) {
            $addr = trim(utf8_encode($addr));
            $this->pushTo('address', $addr);
        }

        return true;
    }

    protected function parseNewConsent($bg, $betNr, $clearing, $account, $orgNr, $status)
    {
        if ( !$this->validBg($bg) ) return false;

        self::buildStateIdNr($orgNr, $orgNrType);

        $this->nrOfPosts++;

        $consent = array(
            'tc' => '52',
            'betNr' => ltrim($betNr, '0'),
            'account' => self::buildAccountNr($betNr, $clearing, $account),
            $orgNrType => $orgNr,
            'status' => $status,
            'statusMsg' => $this->statusMsgs[$status],
        );

        $this->push($consent);

        return true;
    }

    protected function parseFoot($date, $nrPosts)
    {
        if ( !$this->validDate($date) ) return false;
        if ( (int)$nrPosts != $this->nrOfPosts ) {
            $this->error(sprintf(_("Unvalid file content, wrong number of type '%s' posts"), "52, 53, 54, 55 and 56"));
            return false;
        }
        $this->writeSection();

        return true;
    }
}
