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
 * @package itbz\swegiro\BB
 */

namespace itbz\swegiro\BG;

use itbz\swegiro\Char80;

/**
 * BgMax file format parser.
 *
 * @package itbz\swegiro\Bg
 */
class Max extends Char80
{

    /**
     * Nr of transactions globaly in file
     *
     * @var int
     */
    private $transacts = 0;

    /**
     * Nr of deductions globaly in file
     *
     * @var int
     */
    private $deducts = 0;

    /**
     * Nr of external references globaly in file
     *
     * @var int
     */
    private $extRefs = 0;

    /**
     * Nr of deposits globaly in file
     *
     * @var int
     */
    private $deposits = 0;


    /**
     * Channel descriptions
     *
     * @var array
     */
    private $channels = array(
        1 => "Elektronisk betalning från bank.",
        2 => "Elektronisk betalning från tjänsten Leverantörsbetalningar (LB).",
        3 => "Blankettbetalning.",
        4 => "Elektronisk betalning fråm tjänsten Autogiro (AG).",
    );


    /**
     * Refenrece code descriptions
     *
     * @var array
     */
    private $refCodes = array(
        0 => "Ingen referens. Extra referensnummer kan förekomma.",
        1 => "Ingen referens, avtal om blankettregistrering saknas.",
        2 => "Korrekt referens enligt avtal om OCR-referenskontroll samt utökad blankettregistrering.",
        3 => "En elle flera referenser, korrekta referenser redovisas som Extra referensnummer.",
        4 => "Korrekt referens enligt avtal om utökad blankettregistrering.",
        5 => "Felaktig referens.",
    );


    /**
     * Deuction code descriptions
     *
     * @var array
     */
    private $deductCodes = array(
        0 => "Helt avdrag, ingen rest.",
        1 => "Delavdrag, rest finns.",
        3 => "Slutligt avdrag där delavdrag förekommit, ingen rest.s",
    );


    /**
     * Layout id
     *
     * @var string
     */
    protected $layout = 'BGMAX';
    protected $layoutNames = array();


    /**
     * Regex represention a valid file structure
     *
     * @var string
     */
    protected $struct = "/^01(05(2[01](2[235])*(26)?(27)?(28)?(29)?)+15)+70$/";


    /**
     * Map transaction codes (TC) to line-parsing regexp and receiving method
     *
     * @var array
     */
    protected $map = array(
        '01' => array('/^01BGMAX\s{15}(\d{2})(\d{20})(T|P)\s*$/', 'parseHead'),
        '05' => array('/^05(\d{10})(.{10})((?:SEK)|(?:EUR))\s*$/', 'parseOpening'),
        '20' => array('/^20(\d{10})(.{25})(\d{18})(\d)(\d)(.{0,12})(\d)?\s*$/', 'parseTransaction'),
        '21' => array('/^21\d{10}(.{25})(\d{18})(\d)\d(.{12})(\d)(\d)\s*$/', 'parseDeduction'),
        '22' => array('/^(22)(\d{10})(.{25})(\d{18})(\d)(\d)(.{12})(\d)\s*$/', 'extrarefpost'),
        '23' => array('/^(23)(\d{10})(.{25})(\d{18})(\d)(\d)(.{12})(\d)\s*$/', 'extrarefpost'),
        '25' => array('/^25(.{50})\s*$/', 'parseInfo'),
        '26' => array('/^26(.{0,35})(.{0,35})\s*$/', 'parseAddress'),
        '27' => array('/^27(.{0,35})(.{0,9})\s*$/', 'parseAddress'),
        '28' => array('/^28(.{0,35})(.{0,35})(.{0,2})\s*$/', 'parseAddress'),
        '29' => array('/^29(\d{12})\s*$/', 'parseOrgNr'),
        '15' => array('/^15.{19}(\d{4})(\d{12})(\d{8})(\d{5})(\d{18})((?:SEK)|(?:EUR))(\d{8})(K|D|S|\s)?\s*$/', 'parseEnd'),
        '70' => array('/^70(\d{8})(\d{8})(\d{8})(\d{8})\s*$/', 'parseFoot'),
    );


    /**
     * Parse head
     *
     * @param string $ver
     *
     * @param string $time
     *
     * @param string $test
     *
     * @return bool true if succesfull, false on failure
     */
    protected function parseHead($ver, $time, $test)
    {
        $this->layout = "BGMAX ".(int)$ver;
        $this->setValue('date', substr($time, 0, 8), true);
        $this->setValue('datetime', $time, true);
        if ( $test == 'T' ) $this->setValue('test', true);

        return true;
    }


    /**
     * Parse opening
     *
     * @param string $bgTo
     *
     * @param string $pgTo
     *
     * @param string $currency
     *
     * @return bool true if succesfull, false on failure
     */
    protected function parseOpening($bgTo, $pgTo, $currency)
    {
        $this->setValue('bgTo', ltrim($bgTo, '0'));
        $pgTo = ltrim($pgTo, '0 ');
        if ( $pgTo ) $this->setValue('pgTo', $pgTo);
        $this->setValue('currency', $currency);

        return true;
    }


    /**
     * Parse transaction
     *
     * @param string $bgFrom
     *
     * @param string $ref
     *
     * @param string $amount
     *
     * @param string $refCode
     *
     * @param string $channel
     *
     * @param string $nr
     *
     * @param string $img
     *
     * @return bool true if succesfull, false on failure
     */
    protected function parseTransaction($bgFrom, $ref, $amount, $refCode, $channel, $nr = false, $img = false)
    {
        $this->transacts++;
        $t = array(
            'bgFom' => ltrim($bgFrom, '0'),
            'ref' => trim(utf8_encode($ref)),
            'refCode' => $refCode,
            'refCodeDesc' => $this->refCodes[$refCode],
            'amount' => $this->str2amount($amount),
            'channel' => $channel,
            'channelDesc' => $this->channels[$channel],
        );
        if ( $nr !== false ) $t['nr'] = ltrim($nr, '0'); 
        if ( $img == "1" ) $t['img'] = true;
        $this->push($t);

        return true;
    }


    /**
     * Parse deduction
     *
     * @param string $ref
     *
     * @param string $amount
     *
     * @param string $refCode
     *
     * @param string $nr
     *
     * @param string $img
     *
     * @param string $deductCode
     *
     * @return bool true if succesfull, false on failure
     */
    protected function parseDeduction($ref, $amount, $refCode, $nr, $img, $deductCode)
    {
        $this->deducts++;
        $post = array(
            'ref' => trim(utf8_encode($ref)),
            'refCode' => $refCode,
            'refCodeDesc' => $this->refCodes[$refCode],
            'amount' => $this->str2amount($amount),
            'deductionCode' => $deductCode,
            'deductionDesc' => $this->deductCodes[$deductCode],
        );
        if ( $img == "1" ) $post['img'] = true;
        $this->pushTo('deductions', $post);

        return true;
    }


    /**
     * Extrarefpost
     *
     * @param string $tc
     *
     * @param string $bgFrom
     *
     * @param string $ref
     *
     * @param string $amount
     *
     * @param string $refCode
     *
     * @param string $channel
     *
     * @param string $nr
     *
     * @param string $img
     *
     * @return bool true if succesfull, false on failure
     */
    protected function extrarefpost($tc, $bgFrom, $ref, $amount, $refCode, $channel, $nr, $img)
    {
        $this->extRefs++;
        
        $amount = $this->str2amount($amount);
        //$tc 23 means negative amount
        if ( $tc == "23" ) $amount = "-$amount";

        $post = array(
            //'bgFom' => ltrim($bgFrom, '0'),
            'ref' => trim(utf8_encode($ref)),
            'refCode' => $refCode,
            'refCodeDesc' => $this->refCodes[$refCode],
            'amount' => $amount,
            //'channel' => $channel,
            //'nr' => ltrim($nr, '0'),
        );
        if ( $img == "1" ) $post['img'] = true;

        $this->pushTo('extraRefs', $post);

        return true;
    }


    /**
     * Parse info
     *
     * @param string $msg
     *
     * @return bool true if succesfull, false on failure
     */
    protected function parseInfo($msg)
    {
        $this->pushTo('info', trim(utf8_encode($msg)));

        return true;
    }


    /**
     * Parse address
     *
     * @return bool true if succesfull, false on failure
     */
    protected function parseAddress()
    {
        foreach ( func_get_args() as $arg ) {
            $this->pushTo('address', trim(utf8_encode($arg)));
        }

        return true;
    }


    /**
     * Parse org number
     *
     * @param string $orgNr
     *
     * @return bool true if succesfull, false on failure
     */
    protected function parseOrgNr($orgNr)
    {
        $t = $this->pop();
        $t['orgNr'] = ltrim($orgNr, '0');
        $this->push($t);

        return true;
    }


    /**
     * Parse end
     *
     * @param string $clearing
     *
     * @param string $account
     *
     * @param string $date
     *
     * @param string $nr
     *
     * @param string $sumTrans
     *
     * @param string $currency
     *
     * @param string $nrTrans
     *
     * @param string $type
     *
     * @return bool true if succesfull, false on failure
     */
    protected function parseEnd($clearing, $account, $date, $nr, $sumTrans, $currency, $nrTrans, $type = false)
    {
        $this->deposits++;
        
        $account = $clearing.",".ltrim($account, '0');
        $this->setValue('accountTo', $account);
        
        //write $date to all transactions i stack
        //count deduction posts
        //sum deduction posts
        $stack = $this->getStack();
        $this->clearStack();
        $deductsInStack = 0;
        $sumDeductsInStack = 0;
        foreach ( $stack as $s ) {
            if ( array_key_exists('deductions', $s) && is_array($s['deductions']) ) {
                $deductsInStack += count($s['deductions']);
                foreach ( $s['deductions'] as $ded ) $sumDeductsInStack += $ded['amount'];
            }
            $s['date'] = $date;
            $this->push($s);
        }

        $this->setValue('nr', ltrim($nr, '0'));

        if ( !$this->setValue('currency', $currency) ) {
            $this->error(_("Unvalid currency"));
            return false;
        }

        $type = trim($type);
        if ( $type ) $this->setValue('type', $type);
        
        if ( (int)$nrTrans != ($this->count()+$deductsInStack) ) {
            $this->error(_("Unvalid file content, wrong number of transaction posts."));
            return false;
        }
        
        if ( $this->str2amount($sumTrans) != ($this->sum('amount')-$sumDeductsInStack) ) {
            $this->error(_("Unvalid file content, wrong transaction sum."));
            return false;
        }
        $this->writeSection();        

        return true;
    }


    /**
     * Parse foot
     *
     * @param string $transacts
     *
     * @param string $deducts
     *
     * @param string $extRefs
     *
     * @param string $deposits
     *
     * @return bool true if succesfull, false on failure
     */
    protected function parseFoot($transacts, $deducts, $extRefs, $deposits)
    {
        if ( (int)$transacts != $this->transacts ) {
            $this->error(_("Unvalid file content, wrong number of transaction posts."));
            return false;
        }
        if ( (int)$deducts != $this->deducts ) {
            $this->error(_("Unvalid file content, wrong number of deduction posts."));
            return false;
        }
        if ( (int)$extRefs != $this->extRefs ) {
            $this->error(_("Unvalid file content, wrong number of extra references."));
            return false;
        }
        if ( (int)$deposits != $this->deposits ) {
            $this->error(_("Unvalid file content, wrong number of deposit posts."));
            return false;
        }

        return true;
    }

}
