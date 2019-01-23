<?php
namespace iio\swegiro\AG;

/**
 * List of consents registered with the bank.
 *
 * Utdrag ur medgivanderegistret
 */
class J extends Object
{
    public function __construct($customerNr = false, $bg = false)
    {
        parent::__construct($customerNr, $bg);
        $this->setMap();
    }

    protected $regexp = array('/^(\d{10})(\d{12})(\d{16})(\d)(\d)(\d{8})(.{8})(\d)(.?)(.{0,5})(\d{0,4})(\d{0,12})/', 'parseConsent');

    protected function setMap()
    {
        for ( $i=0; $i<10; $i++ ) {
            $this->map[$i] = $this->regexp;
        }
    }

    /**
     * Status descriptions
     */
    protected $statusMsgs = array(
        1 => "Godkännt för Autogiro",
        2 => "Under förfrågan",
    );

    /**
     * Validation descriptions
     */
    protected $validMsgs = array(
        0 => "Medgivande OK",
        1 => "Medgivande stoppat",
    );

    /**
     * Source descriptions
     */
    protected $sourceMsgs = array(
        1 => "Medgivande initierat av betalningsmottagare",
        2 => "Medgivande initierat av betalare via internetbank",
    );

    protected function parsingComplete()
    {
        $this->writeSection();
    }

    /**
     * Parse consent
     */
    protected function parseConsent($bg, $orgNr, $betNr, $source, $activeYear, $created, $changed, $status1, $status2=false, $maxAmount=false, $clearing=false, $account=false)
    {
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
