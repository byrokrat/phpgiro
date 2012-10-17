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
 * @package itbz\swegiro
 */

namespace itbz\swegiro;

/**
 * Contains static methods for creating and validating
 * ocr numbers and check digits.
 *
 * @package itbz\swegiro
 */
class OCR
{

    /**
     * Internal ocr representation
     * @var string $ocr
     */
    protected $ocr;

    /**
     * Amount as array: 0=>units, 1=>cents
     * @var array $amount
     */
    protected $amount;

    /**
     * Internal account representation
     * @var string $account
     */
    protected $account;


    /**
     * Create an optionally set account, reference and amount. If you don't
     * want your ocr to contain a length check digit, or if
     * you already have a valid ocr, use setRef() or setOcr() instead.
     * @param int|string $account
     * @param int|string $ref
     * @param int|string $amount
     */
    public function __construct($account=false, $ref=false, $amount=false){
        if ( $account ) $this->setAccount($account);
        if ( $ref ) $this->setRef($ref);
        if ( $amount ) $this->setAmount($amount);
    }


    /**
     * Set target account number
     * @param string $account
     * @return bool true on success, false if $account is not valid
     */
    public function setAccount($account){
        if ( self::validCheckDigit($account) ) {
            $this->account = $account;
            return true;
        } else {
            return false;
        }
    }


    /**
     * Get account
     * @return string
     */
    public function getAccount(){
        return $this->account;
    }


    /**
     * Set amount. Divide units and cents using a dot (.) .
     * @param int|string $amount
     * @return void
     */
    public function setAmount($amount){
        $amount = (string)$amount;
        $this->amount = explode('.', $amount);
        if ( count($this->amount) == 1 ) $this->amount[1] = 00;
        $this->amount[1] = str_pad($this->amount[1], 2, "0", STR_PAD_RIGHT);
    }


    /**
     * Get ammount
     * @return array
     */
    public function getAmount(){
        return $this->amount;
    }


    /**
     * Set ocr number. Ocr must have a valid check digit.
     * If you want the check digit computed use setRef() instead.
     * @param int|string $ocr
     * @param bool $checkLength
     * @return true on success, false if ocr is not valid
     */
    public function setOcr($ocr, $checkLength=false){
        if ( self::validOCR($ocr, $checkLength) ) {
            $this->ocr = $ocr;
            return true;
        } else {
            return false;
        }
    }


    /**
     * Set reference OCR. Check digits will be computed.
     * @param int|string $ref
     * @param bool $checkLength
     */
    public function setRef($ref, $checkLength=true){
        $this->ocr = self::makeOCR($ref, $checkLength);
    }

    /**
     * Get ocr number
     * @return string
     */
    public function getOcr(){
        return $this->ocr;
    }

    /**
     * Takes an arbitrary nr and calculates a ocr
     * check number. Appends the check number and
     * returns the complete OCR. Also creates a
     * legth check number is $checkLength == true
     *
     * Since long references are impossible to represent
     * as integers on 32 bit systems $nr may be a string. 
     *
     * @param int|string $nr
     * @param bool $checkLength
     * @return string
     */
    public static function makeOCR($nr, $checkLength=true){
        $nr = (string)$nr;
        if ( $checkLength ) {
            $length = strlen($nr);
            $length += 2;
            $length = $length % 10;
            $nr .= $length;
        }
        $check = self::getCheckDigit($nr);
        $nr .= $check;
        return strlen($nr)>25 ? false : $nr;
    }


    /**
     * Validate an OCR number. If $checkLength==true length check
     * digit will also be validated.
     * @param int|string $nr
     * @param bool $checkLength
     * @return bool true of valid, false if not
     */
    public static function validOCR($nr, $checkLength=true){
        $remove = ($checkLength) ? -2 : -1;
        $stripped = substr($nr, 0, $remove);
        $ocr = self::makeOCR($stripped, $checkLength);
        return ( $ocr==$nr && strlen($ocr)<=25 && strlen($ocr)>=2 );
    }


    /**
     * Returns true if last position of $nr is a valid
     * modulus 10 check digit, false otherwise.
     * @param int|string $nr
     * @return bool
     */
    public static function validCheckDigit($nr){
        return self::validOCR($nr, false);
    }


    /**
     * Get modulo 10 check digit. Either for creating OCR numbers
     * or for validationg transaction amounts.
     * @param string|int $nr
     * @return int
     */
    public static function getCheckDigit($nr){
        $nr = (string)$nr;
        $n = 2;
        $sum = 0;
        
        for ($i=strlen($nr)-1; $i>=0; $i--) {
            $tmp = $nr[$i] * $n;
            ($tmp > 9) ? $sum += 1 + ($tmp % 10) : $sum += $tmp;
            ($n == 2) ? $n = 1 : $n = 2;
        }
        
        $ceil = $sum;
        while ( $ceil % 10 != 0 ) {
            $ceil++;
        }
        
        $check = $ceil-$sum;
        return $check;
    }

}
