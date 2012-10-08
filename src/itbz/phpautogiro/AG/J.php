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
 * AG layout J, list of consents registered with the bank.
 *
 * @package itbz\phpautogiro\AG
 */
class J extends Object
{

    /**
     * Status descriptions
     *
     * @var array
     */
    protected $statusMsgs = array(
        1 => "Godkännt för Autogiro",
        2 => "Under förfrågan",
    );


    /**
     * Validation descriptions
     *
     * @var array
     */
    protected $validMsgs = array(
        0 => "Medgivande OK",
        1 => "Medgivande stoppat",
    );


    /**
     * Source descriptions
     *
     * @var array
     */
    protected $sourceMsgs = array(
        1 => "Medgivande initierat av betalningsmottagare",
        2 => "Medgivande initierat av betalare via internetbank",
    );


    /* FILE STRUCTURE */


    /**
     * Transaction code caracter length.
     *
     * @var int
     */
    protected $tcLength = 1;


    /**
     * Layout id
     *
     * @var string
     */
    protected $layout = 'J';


    /**
     * Map all transaction codes (TC) to line-parsing regexp and receiving method
     *
     * @var array
     */
    protected $regexp = array('/^(\d{10})(\d{12})(\d{16})(\d)(\d)(\d{8})(.{8})(\d)(.?)(.{0,5})(\d{0,4})(\d{0,12})/', 'parseConsent');


    /**
     * AG layout J, list of consents registered with the bank.
     *
     * @param string $customerNr
     *
     * @param string $bg
     */
    public function __construct($customerNr = FALSE, $bg = FALSE)
    {
        parent::__construct($customerNr, $bg);
        $this->setMap();
    }


    /**
     * Set the same regexp for 0-9, this layout does not contain TC
     * @return void
     */
    protected function setMap(){
        for ( $i=0; $i<10; $i++ ) {
            $this->map[$i] = $this->regexp;
        }
    }


    /* PARSING FUNCTIONS */

    /**
     * Write section on parseing complete
     * @return void
     */
    protected function parsingComplete(){
        $this->writeSection();
    }

    
    /**
     * Parse consent
     * @param string $bg
     * @param string $orgNr
     * @param string $betNr
     * @param string $source
     * @param string $activeYear
     * @param string $created
     * @param string $changed
     * @param string $status1
     * @param string $status2
     * @param string $maxAmount
     * @param string $clearing
     * @param string $account
     * @return bool TRUE on success, FALSE on failure
     */
    protected function parseConsent($bg, $orgNr, $betNr, $source, $activeYear, $created, $changed, $status1, $status2=false, $maxAmount=false, $clearing=false, $account=false){
        self::buildStateIdNr($orgNr, $orgNrType);
        
        $c = array(
            'toBg' => ltrim($bg, '0'),
            $orgNrType => $orgNr,
            'betNr' => ltrim($betNr, '0'),
            'source' => (int)$source,
            'sourceMsg' => $this->sourceMsgs[(int)$source],
            'lastActiveYear' => $activeYear,
            'created' => $created,
            'changed' => trim($changed),
            'account' => self::buildAccountNr($betNr, $clearing, $account),
            'status' => (int)$status1,
            'statusMsg' => $this->statusMsgs[(int)$status1],
        );
        
        if ( $status2 !== false && !preg_match("/^\s*$/", $status2) ) {
            $c['valid'] = (int)$status2;
            $c['validMsg'] = $this->validMsgs[(int)$status2];
        }
        
        if ( $maxAmount && !preg_match("/^\s*$/", $maxAmount) ) {
            $c['maxAmount'] = $maxAmount;
        }
        
        $this->push($c);
        return true;
    }

}
