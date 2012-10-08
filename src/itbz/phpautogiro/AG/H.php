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
 * @package itbz\phpautogiro\AG
 */

namespace itbz\phpautogiro\AG;

/**
 * AG layout H, new consents from the bank.
 *
 * <code>
 * <b>Produces stack items with the following layout:</b>
 * [betNr] =>
 * [account] => account nr, regular or BG
 * [orgNr/persNr] => swedish social security number, or arganizational number
 * [status] => status code
 * [statusMsg] => string message describing status
 * [info] => array of addition info
 * [address] => array
 * </code>
 *
 * @package itbz\phpautogiro\AG
 */
class H extends Object
{

    /**
     * Txt status messages
     *
     * @var array
     */
    protected $statusMsgs = array(
        0 => "Första meddelandet",
        1 => "Påminnelse nummer ett",
        2 => "Påminnelse nummer två",
    );

    /**
     * Internal count of posts in section
     *
     * @var int
     */
    private $nrOfPosts = 0;


    /**
     * Extend sectionClear() to also clear posts in section
     *
     * @return void
     */
    public function sectionClear()
    {
        $this->nrOfPosts = 0;

        return parent::sectionClear();
    }



    /**
     * Layout id
     *
     * @var string
     */
    protected $layout = 'H';


    /**
     * Regex represention a valid file structure
     *
     * @var string
     */
    protected $struct = "/^(51(52(5[3456])*)+59)+$/";


    /**
     * Map transaction codes (TC) to line-parsing regexp and receiving method
     *
     * @var array
     */
    protected $map = array(
        '51' => array("/^51(\d{8})9900(\d{10})AG-EMEDGIV/", 'parseHeadDateBg'),
        '52' => array("/^52(\d{10})(\d{16})(\d{4})(\d{12})(\d{12}).{5}(\d)/", 'parseNewConsent'),
        '53' => array("/^53(.{0,36})/", 'parseInfo'),
        '54' => array("/^54(.{0,36})(.{0,36})/", 'parseAddress'),
        '55' => array("/^55(.{0,36})(.{0,36})/", 'parseAddress'),
        '56' => array("/^56(.{0,5})(.{0,31})/", 'parseAddress'),
        '59' => array("/^59(\d{8})9900(\d{7})/", 'parseFoot'),
    );


    /**
     * Parse info
     *
     * @param string $info
     *
     * @return bool true on success, false on failure
     */
    protected function parseInfo($info){
        $this->nrOfPosts++;
        $info = trim(utf8_encode($info));
        $this->pushTo('info', $info);
        return true;
    }


    /**
     * Parse address
     *
     * @param string $addr1
     *
     * @param string $addr2
     *
     * @return bool true on success, false on failure
     */
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


    /**
     * Parse new consent
     *
     * @param string $bg
     *
     * @param string $betNr
     *
     * @param string $clearing
     *
     * @param string $account
     *
     * @param string $orgNr
     *
     * @param string $status
     *
     * @return bool true on success, false on failure
     */
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

 
    /**
     * Parse foot
     *
     * @param string $date
     *
     * @param string $nrPosts
     *
     * @return bool true on success, false on failure
     */
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
