<?php
namespace itbz\swegiro\AG;

/**
 * Feedback on new and removed consents.
 *
 * Medgivandeavisering
 */
class E extends Object
{
    protected $struct = "/^(01(73)*09)+$/";

    protected $map = array(
        '01' => array("/^01(\d{8})9900(\d{10})AG-MEDAVI\s*$/", 'parseHeadDateBg'),
        '73' => array("/^73(\d{10})(\d{16})(.{4})(.{12})(.{12}).{5}(\d\d)(\d\d)(\d{8})(\d{0,6})\s*$/", 'parseConsentInfo'),
        '09' => array("/^09(\d{8})9900(\d{7})\s*$/", 'parseFoot'),
    );

    /**
     * Status descriptions
     */
    protected $statusMsgs = array(
        2 => "Medgivandet är makulerat på initiativ av betalaren.",
        3 => "Kontoslaget är inte godkänt för autogiro",
        4 => "Medgivandet saknas i Bankgirots medgivanderegister.",
        5 => "Felaktiga bankkonto- eller personuppgifter.",
        7 => "Medgivandet är makulerat av Bankgirot på grund av obesvarad kontoförfrågan.",
        9 => "Bankgironummret saknas hos Bankgirot.",
        10 => "Medgivandet finns redan upplagt i Bankgirots register eller är under förfrågan.",
        20 => "Felaktigt personnummer.",
        21 => "Felaktigt betalarnummer.",
        23 => "Felaktigt bankkontonummer.",
        29 => "Felaktigt mottagande bankgironummer.",
        30 => "Mottagarbankgironummer saknas.",
        32 => "Nytt medgivande.",
        33 => "Makulerad.",
        98 => "Medgivandet är makulerat på grund av makulerat betalarnummer.",
    );

    /**
     * TC == 73, register post
     */
    protected function parseConsentInfo($bg, $betNr, $clearing, $account, $orgNr, $info, $status, $date, $validDate=false)
    {
        if ( !$this->validBg($bg) ) return false;
        
        //Set action
        $action = "?";
        switch ( $info ) {
            case "05":
                if ( $status == "33" ) {
                    $action = 'D';
                    break;
                }
            case "04":
            case "42":
                $action = ( $status=="32" ) ? 'A' : 'E';
                break;

            case "03":
                $action = ( $status=="33" ) ? 'D' : 'E';
                break;
            
            case "10":
            case "43":
            case "44":
            case "46":
                $action = "D";
        }

        //set pers/org number
        self::buildStateIdNr($orgNr, $orNrType);
    
        $consent = array(
            'action' => $action,
            'betNr' => ltrim($betNr, '0'),
            'account' => self::buildAccountNr($betNr, $clearing, $account),
            'info' => (int)$info,
            'status' => (int)$status,
            'statusMsg' => $this->statusMsgs[(int)$status],
            'date' => $date,
            $orNrType => $orgNr,
        );

        //set valid from
        if ( !preg_match("/^[0 ]*$/", $validDate) ) {
            $consent['validFrom'] = $validDate;
        }

        $this->push($consent);
        return true;
    }

    /**
     * TC == 09, footer E style
     */
    protected function parseFoot($date, $nrPosts)
    {
        if ( !$this->validDate($date) ) return false;
        if ( (int)$nrPosts != $this->count() ) {
            $this->error(sprintf(_("Unvalid file content, wrong number of type '%s' posts"), "73"));
            return false;
        }
        $this->writeSection();
        return true;
    }
}
