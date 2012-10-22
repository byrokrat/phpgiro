<?php
namespace itbz\swegiro\AG;

/**
 * List of transactions registered with the bank, but not processed.
 *
 * Utrag ur bevakningsregistret
 *
 * <code>
 * <b>Produces stack items with the following:</b>
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
 */
class I extends Object
{
    protected $struct = "/^(01([83]2)+09)+$/";

    protected $map = array(
        '01' => array("/^01(\d{8})AUTOGIRO9900BEVAKNINGSREG.{27}(\d{6})(\d{10})/", 'parseHeadDateCustBg'),
        '82' => array("/^([83]2)(\d{8})(.)(...).(\d{16})(\d{12})/", 'parseTransaction'),
        '32' => array("/^([83]2)(\d{8})(.)(...).(\d{16})(\d{12})/", 'parseTransaction'),
        '09' => array("/^09(\d{8})9900.{14}(\d{12})(\d{6})(\d{6}).{4}(\d{12})/", 'parseTransactionFoot'),
    );
}
