<?php
namespace iio\swegiro\AG;

/**
 * Feedback on rejected transactions.
 *
 * Avvisade betalningsuppdrag
 */
class F extends Object
{
    protected $struct = "/^(01([83]2)+09)+$/";

    protected $map = array(
        '01' => array("/^01(\d{8})AUTOGIRO9900FELLISTA REG.KONTRL.{21}(\d{6})(\d{10})/", 'parseHeadDateCustBg'),
        '82' => array("/^([83]2)(\d{8})(.)(...)(\d{16})(\d{12})()(.{16})(\d\d)/", 'parseTransaction'),
        '32' => array("/^([83]2)(\d{8})(.)(...)(\d{16})(\d{12})()(.{16})(\d\d)/", 'parseTransaction'),
        '09' => array("/^09(\d{8})9900(\d{6})(\d{12})(\d{6})(\d{12})/", 'parseTransactionFoot'),
    );

    /**
     * Messages describing transaction status
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

    protected function parseTransactionFoot($date, $nrCredit, $sumCredit, $nrInvoice, $sumInvoice)
    {
        return parent::parseTransactionFoot($date, $sumCredit, $nrCredit, $nrInvoice, $sumInvoice);
    }
}
