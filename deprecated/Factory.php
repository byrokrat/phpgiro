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
 * @package itbz\phpautogiro
 */

namespace itbz\phpautogiro;

/**
 * <h3>Supported file formats</h3>
 *
 * Layouts A - H in the legacy Autogiro Privat.
 *
 * Layouts A - C and E - J in new Autogiro (in use fall 2011). (Support for
 * layout D is currently missing, but the BgMax format can be used instead.)
 *
 * Bankgirot standard format BgMax
 *
 * PlusGirot layout N (also known as 02P).
 *
 * <h4>Supported AUTOGRIO formats</h4>
 * <pre>
 * +=============================+=====+=====+=====+
 * | LAYOUT                      | PRI | OLD | NEW |
 * +=============================+=====+=====+=====+
 * | A (Medgivandeunderlag)      |  X  |  X  |  X  |
 * +-----------------------------+-----+-----+-----+
 * | B (Betalningsunderlag)      |  X  |  X  |  X  |
 * +-----------------------------+-----+-----+-----+
 * | C (Mak./ändr. bet.underlag) |  X  |  X  |  X  |
 * +-----------------------------+-----+-----+-----+
 * | D (Betalningsspec.)         |  X  |  X  |  ?  |
 * +-----------------------------+-----+-----+-----+
 * | BGMAX (Betalningsspec.)     |  -  |  -  |  X  |
 * +-----------------------------+-----+-----+-----+
 * | E (Medgivandeavisering)     |  X  |  X  |  X  |
 * +-----------------------------+-----+-----+-----+
 * | F (Avvisade bet.)           |  X  |  X  |  X  |
 * +-----------------------------+-----+-----+-----+
 * | G (Mak./ändrings-lista)     |  X  |  X  |  X  |
 * +-----------------------------+-----+-----+-----+
 * | H (Elektr. medgivanden)     |  X  |  X  |  X  |
 * +-----------------------------+-----+-----+-----+
 * | I (Utdrag bevakningsreg)    |  -  |  X  |  X  |
 * +-----------------------------+-----+-----+-----+
 * | J (Utdrag medgivandereg)    |  -  |  X  |  X  |
 * +=============================+=====+=====+=====+
 *   PRI = autogiro privat
 *   OLD = new autogiro with old layout
 *   NEW = nya autogiro with nwe layout
 * </pre>
 *
 * @package itbz\phpautogiro
 */
class Factory
{

    /**
     * Autodiscover layout and get an Char80 object to process.
     *
     * <h4>Usage</h4>
     *
     * <code>include('GiroFactory.class.php');
     * use PhpGiro\GiroFactory;
     * $giroObj = GiroFactory::parse('myBankfile.txt');
     * if ( $giroObj->hasError() ) print_r($giroObj->getErrors());
     * while ( $section = $giroObj->getSection() ) {
     *     print_r($section);
     * }</code>
     *
     * @param string $fname
     * @return Char80
     * @api
     */
    public function parse($fname){

        $register = array(
            'PhpGiro_AG_New_D' => 'BET. SPEC & STOPP TK',
            'PhpGiro_AG_New_E' => array('AUTOGIRO', 'AG-MEDAVI'),
            'PhpGiro_AG_E' => 'AG-MEDAVI',
            'PhpGiro_AG_F' => 'FELLISTA REG.KONTRL',
            'PhpGiro_AG_New_F' => 'AVVISADE BET UPPDR',
            'PhpGiro_AG_G' => utf8_decode("MAK/ÄNDRINGSLISTA"),
            'PhpGiro_AG_New_G' => utf8_decode("MAKULERING/ÄNDRING"),
            'PhpGiro_AG_H' => "AG-EMEDGIV",
            'PhpGiro_AG_I' => "BEVAKNINGSREG",

            'PhpGiro_BG_Max' => "BGMAX"
        );

        $line = $this->getFirstLine($fname);

        foreach ( $register as $class => $ptrn ) {
            $match = true;
            if ( is_array($ptrn) ) {
                foreach ( $ptrn as $p ) {
                    if ( strpos($line, $p) === false ) $match = false;
                }
            } else {
                if ( strpos($line, $ptrn) === false ) $match = false;
            }
            if ( $match ) {
                $obj = new $class();
                $obj->readFile($fname);
                $obj->parse();
                return $obj;
            }
        }

        $last =    $this->getLastLine($fname);

        if ( strpos($line, "AUTOGIRO") !== false ) {
            if ( strpos($last, '09') === 0 ) {
                $class = "PhpGiro_AG_D";
            } else {
                $class = "PhpGiro_AG_ABC";
            }
            
        } else {
            if ( strpos($last, '90') === 0 ) {
                $class = "PhpGiro_PG_N";
            } else {
                $class = "PhpGiro_AG_New_J";
                //alternativt
                //$class = "AgLayoutJ";
            }
        }

        $obj = new $class();
        $obj->readFile($fname);
        $obj->parse();
        return $obj;
    }


    /**
     * Get first line from file, for analyzing layout.
     * Returns false if an error occured.
     * @param string $fname
     * @return string
     */
    private function getFirstLine($fname){
        $fhand = fopen($fname, "r");
        if ( $fhand === false ) return false;
        do {
            $line = fgets($fhand);
            if ( $line === false ) return false;
        } while ( preg_match("/^\s*$/", $line) ) ;
        fclose($fhand);
        return $line;
    }


    /**
     * Get last line from file, for analyzing layout.
     * Returns false if an error occured.
     * @param string $fname
     * @return string
     */
    private function getLastLine($fname){
        $last = "";
        $fhand = fopen($fname, "r");
        if ( $fhand === false ) return false;
        while ( $line = fgets($fhand) ) {
            if ( !preg_match("/^\s*$/", $line) )  {
                $last = $line;
            }
        }
        return $last;        
        fclose($fhand);
        return $line;
    }

}
