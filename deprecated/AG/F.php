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
 * @package itbz\swegiro\AG
 */

namespace itbz\swegiro\AG;

/**
 * AG layout F, feedback on rejected transactions.
 *
 * @package itbz\swegiro\AG
 */
class F extends Object
{

    /**
     * Messages describing transaction status
     *
     * @var array
     */
    protected $statusMsgs = array(
        1 => "Utgår, medgivande saknas.",
        2 => "Utgår, bankkontot är ännu ej godkänt, alternativt ännu ej debiterbart, alternativt avslutat.",
        3 => "Utgår, medgivande stoppat.",
        4 => "Felaktigt betalarnummer.",
        5 => "Felaktigt bankgironummer.",
        6 => "Felaktig periodkod.",
        7 => "Avvisad, bankkonto ännu ej debiterbart (autogiro privat), alternativt felaktigt antal självförnyande uppdrag (nya autogirot).",
        8 => "Beloppet är inte numeriskt.",
        9 => "Förbud mot utbetalningar.",
        10 => "Bankgironummret saknas hos Bankgirot.",
        12 => "Felaktigt betalningsdatum.",
        13 => "Passerat betalningsdatum.",
        15 => "Bankgironummret i öppningsposten och i transaktionsposten är inte detsamma.",
        24 => "Beloppet överstiger maxbeloppet.",
    );


    /* FILE STRUCTURE */

    /**
     * Layout id
     * @var string $layout
     */
    protected $layout = 'F';


    /**
     * Regex represention a valid file structure
     * @var string $struct
     */
    protected $struct = "/^(01([83]2)+09)+$/";


    /**
     * Map transaction codes (TC) to line-parsing regexp and receiving method
     * @var array $map
     */
    protected $map = array(
        '01' => array("/^01(\d{8})AUTOGIRO9900FELLISTA REG.KONTRL.{21}(\d{6})(\d{10})/", 'parseHeadDateCustBg'),
        '82' => array("/^([83]2)(\d{8})(.)(...)(\d{16})(\d{12})()(.{16})(\d\d)/", 'parseTransaction'),
        '32' => array("/^([83]2)(\d{8})(.)(...)(\d{16})(\d{12})()(.{16})(\d\d)/", 'parseTransaction'),
        '09' => array("/^09(\d{8})9900(\d{6})(\d{12})(\d{6})(\d{12})/", 'parseTransactionFoot'),
    );


    /**
     * Parse transaction foot
     * @param string $date
     * @param string $nrCredit
     * @param string $sumCredit
     * @param string $nrInvoice
     * @param string $sumInvoice
     * @return bool true on success, false on failure
     */
    protected function parseTransactionFoot($date, $nrCredit, $sumCredit, $nrInvoice, $sumInvoice)
    {
        return parent::parseTransactionFoot($date, $sumCredit, $nrCredit, $nrInvoice, $sumInvoice);
    }

}
