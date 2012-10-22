<?php
namespace itbz\swegiro\AG;

/**
 * Feedback on completed transactions.
 *
 * Betalningsspecifikation
 */
class D extends Object
{
    protected $struct = "/^(01([83]2)+09)+$/";

    protected $map = array(
        '01' => array("/^01(\d{8})AUTOGIRO9900.{40}(\d{6})(\d{10})/", 'parseHeadDateCustBg'),
        '82' => array("/^([83]2)(.{8})(.)(.{3}) (\d{16})(\d{12})(\d{10})(.{0,16}).{0,10}(.)?\s*$/", 'parseTransaction'),
        '32' => array("/^([83]2)(.{8})(.)(.{3}) (\d{16})(\d{12})(\d{10})(.{0,16}).{0,10}(.)?\s*$/", 'parseTransaction'),
        '09' => array("/^09(\d{8})9900.{14}(\d{12})(\d{6})(\d{6})0{4}(\d{12})0*$/", 'parseTransactionFoot'),
    );
}
