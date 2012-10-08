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
        self::LAYOUT_D => array('BET. SPEC & STOPP TK'),
        'PhpGiro_AG_New_E' => array('AUTOGIRO', 'AG-MEDAVI'),
        self::LAYOUT_E => array('AG-MEDAVI'),
        self::LAYOUT_F => array('FELLISTA REG.KONTRL'),
        'PhpGiro_AG_New_F' => array('AVVISADE BET UPPDR'),
        self::LAYOUT_G => array("MAK/ÄNDRINGSLISTA"),
        'PhpGiro_AG_New_G' => array("MAKULERING/ÄNDRING"),
        self::LAYOUT_H => array("AG-EMEDGIV"),
        self::LAYOUT_I => array("BEVAKNINGSREG")
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
        return self::LAYOUT_D;
    }
}
