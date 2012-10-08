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
 * AG layout G, feedback on deletions and changes.
 *
 * @package itbz\phpautogiro\AG
 */
class G extends Object
{

    /**
     * Status descriptions
     *
     * @var array
     */
    protected $statusMsgs = array(
        1 => "Felaktig förfallodag.",
        2 => "Felaktigt betalarnummer.",
        4 => "Felaktig transaktionskod.",
        5 => "Felaktigt belopp.",
        6 => "Felaktig ny förfallodag.",
        10 => "Felaktigt bankgironummer.",
        11 => "Bankgironummer saknas.",
        12 => "Makulerad.",
        13 => "Betalningen saknas, inte åtgärdad.",
        14 => "Ändrad förfallodag.",
        15 => "Inte ändrad, självförnyande uppdrag.",
        18 => "Ändrad.",
        22 => "Ej ändrad.",
    );


    /**
     * Layout id
     *
     * @var string
     */
    protected $layout = 'G';


    /**
     * Regex represention a valid file structure
     *
     * @var string
     */
    protected $struct = "/^(01((2[1-9])|(03)|(11))+09)+$/";


    /**
     * Map transaction codes (TC) to line-parsing regexp and receiving method
     *
     * @var array
     */
    protected $map = array(
        '03' => array("/^((?:03)|(?:2[1-9]))(\d{8})(.{16})(\d\d)(\d{12})(.{8})(.{8})(.{16})(\d\d)/", 'parseMak'),
        '11' => array("/^((?:11)|(?:03)|(?:2[1-9]))(\d{8})(.{16})(\d\d)(\d{12})(.{8})(.{8})(.{16})(\d\d)/", 'parseMak'),
        '21' => array("/^((?:03)|(?:2[1-9]))(\d{8})(.{16})(\d\d)(\d{12})(.{8})(.{8})(.{16})(\d\d)/", 'parseMak'),
        '22' => array("/^((?:03)|(?:2[1-9]))(\d{8})(.{16})(\d\d)(\d{12})(.{8})(.{8})(.{16})(\d\d)/", 'parseMak'),
        '23' => array("/^((?:03)|(?:2[1-9]))(\d{8})(.{16})(\d\d)(\d{12})(.{8})(.{8})(.{16})(\d\d)/", 'parseMak'),
        '24' => array("/^((?:03)|(?:2[1-9]))(\d{8})(.{16})(\d\d)(\d{12})(.{8})(.{8})(.{16})(\d\d)/", 'parseMak'),
        '25' => array("/^((?:03)|(?:2[1-9]))(\d{8})(.{16})(\d\d)(\d{12})(.{8})(.{8})(.{16})(\d\d)/", 'parseMak'),
        '26' => array("/^((?:03)|(?:2[1-9]))(\d{8})(.{16})(\d\d)(\d{12})(.{8})(.{8})(.{16})(\d\d)/", 'parseMak'),
        '27' => array("/^((?:03)|(?:2[1-9]))(\d{8})(.{16})(\d\d)(\d{12})(.{8})(.{8})(.{16})(\d\d)/", 'parseMak'),
        '28' => array("/^((?:03)|(?:2[1-9]))(\d{8})(.{16})(\d\d)(\d{12})(.{8})(.{8})(.{16})(\d\d)/", 'parseMak'),
        '29' => array("/^((?:03)|(?:2[1-9]))(\d{8})(.{16})(\d\d)(\d{12})(.{8})(.{8})(.{16})(\d\d)/", 'parseMak'),

        '09' => array("/^09(\d{8})9900.{14}(.{12})(\d{6})(\d{6})....(.{12})/", 'parseFoot'),
    );

    
    /**
     * Needed for utf8-decode
     *
     * @param string $customerNr
     *
     * @param string $bg
     */
    public function __construct($customerNr = FALSE, $bg = FALSE)
    {
        parent::__construct($customerNr, $bg);
        $this->map['01'] = array(utf8_decode("/^01(\d{8})AUTOGIRO9900MAK\/ÄNDRINGSLISTA.{23}(\d{6})(\d{10})/"), 'parseHeadDateCustBg');
    }



    /* PARSING FUNCTIONS */


    /**
     * Parser mak
     * @param string $tc
     * @param string $date
     * @param string $betNr
     * @param string $code
     * @param string $amount
     * @param string $isRef
     * @param string $newDate
     * @param string $ref
     * @param string $status
     * @return bool TRUE on success, FALSE on failure
     */
    protected function parseMak($tc, $date, $betNr, $code, $amount, $isRef, $newDate, $ref, $status){
        if ( $isRef == "REFERENS" ) {
            $ref = trim(utf8_encode($ref));
        } else {
            $ref = false;
        }

        $transType = '';
        if ( $code == '82' ) $transType = 'I';
        if ( $code == '32' ) $transType = 'C';

        $mak = array(
            'tc' => (int)$tc,
            'name' => $this->tcNames[(int)$tc],
            'date' => $date,
            'betNr' => ltrim($betNr, '0'),
            'transType' => $transType,
            'amount' => $this->str2amount($amount),
            'ref' => $ref,
            'status' => (int)$status,
            'statusMsg' => $this->statusMsgs[(int)$status],
        );
        
        if ( (int)$tc > 25 ) {
            $mak['newDate'] = $newDate;
        }
        
        $this->push($mak);
        return true;
    }


    /**
     * Parse foot
     * @param string $date
     * @param string $sumCredit
     * @param string $nrCredit
     * @param string $nrInvoice
     * @param string $sumInvoice
     * @return bool TRUE on success, FALSE on failure
     */
    protected function parseFoot($date, $sumCredit, $nrCredit, $nrInvoice, $sumInvoice){
        if ( !$this->validDate($date) ) return false;
        //Sums are not to be validated, they are more informational.
        //For now i'm ignoring them...
        $this->writeSection();
        return true;
    }

}
