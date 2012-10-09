<?php
/**
 * This file is part of the STB package
 *
 * Copyright (c) 2012 Hannes Forsgård
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Hannes Forsgård <hannes.forsgard@gmail.com>
 * @package itbz\phpautogiro
 */

namespace itbz\phpautogiro;

/**
 * Sniff the layout type of a AG-string
 *
 * @package itbz\phpautogiro
 */
class Sniffer implements LayoutInterface
{
    /**
     * Identifiers used when sniffing
     * 
     * @var array
     */
    private static $identifiers = array(
        self::LAYOUT_NEW_D  => array('BET. SPEC & STOPP TK'),
        self::LAYOUT_NEW_E  => array('AUTOGIRO', 'AG-MEDAVI'),
        self::LAYOUT_AGP_E  => array('AG-MEDAVI'),
        self::LAYOUT_AGP_F  => array('FELLISTA REG.KONTRL'),
        self::LAYOUT_NEW_F  => array('AVVISADE BET UPPDR'),
        self::LAYOUT_AGP_G  => array("MAK/ÄNDRINGSLISTA"),
        self::LAYOUT_NEW_G  => array("MAKULERING/ÄNDRING"),
        self::LAYOUT_AGP_H  => array("AG-EMEDGIV"),
        self::LAYOUT_NEW_I  => array("BEVAKNINGSREG")
    );

    /**
     * Sniff layout type from file
     * 
     * @param array $lines The file contents
     * 
     * @return integer One of the LayoutInterface flags
     */
    public function sniff(array $lines)
    {
        // Undrar om jag ska skriva den här så att den kan tåla tomma
        // rader i början av $lines!!
        // 
        // skriv test för det först..
        $line = $lines[0];

        foreach (self::$identifiers as $flag => $ids) {
            $match = true;
            foreach ($ids as $id) {
                $id = utf8_decode($id);
                if (strpos($line, $id) === false) {
                    $match = false;
                }
            }
            if ($match) {
                return $flag;
            }
        }


    }
}
