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
 * AG layout I, list of transactions registered with the bank, but not processed.
 *
 * <code>
 * <b>Produces stack items with the following layout:</b>
 * [transType] => I=invoice, C=credit
 * [date] => date when BGC proccessed the transaction
 * [betNr] =>
 * [amount] =>
 * [ref] => custom reference
 * [period] => repitition cycles code
 * [periodMsg] => string describing repitition period
 * [repetitions] => nr of repititions left, -1 means infinite
 * [status] => transaction code
 * [statusMsg] => string message describing status code
 * </code>
 *
 * @package itbz\phpautogiro\AG
 */
class I extends Object
{

    /**
     * Layout id
     *
     * @var string
     */
    protected $layout = 'I';


    /**
     * Regex represention a valid file structure
     *
     * @var string
     */
    protected $struct = "/^(01([83]2)+09)+$/";


    /**
     * Map transaction codes (TC) to line-parsing regexp and receiving method
     * @var array $map
     */
    protected $map = array(
        '01' => array("/^01(\d{8})AUTOGIRO9900BEVAKNINGSREG.{27}(\d{6})(\d{10})/", 'parseHeadDateCustBg'),
        '82' => array("/^([83]2)(\d{8})(.)(...).(\d{16})(\d{12})/", 'parseTransaction'),
        '32' => array("/^([83]2)(\d{8})(.)(...).(\d{16})(\d{12})/", 'parseTransaction'),
        '09' => array("/^09(\d{8})9900.{14}(\d{12})(\d{6})(\d{6}).{4}(\d{12})/", 'parseTransactionFoot'),
    );

}
