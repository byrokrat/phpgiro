<?php
/**
 * This file is part of the swegiro package
 *
 * Copyright (c) 2012 Hannes Forsgård
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Hannes Forsgård <hannes.forsgard@gmail.com>
 * @package itbz\swegiro
 */

namespace itbz\swegiro;

use itbz\swegiro\Exception\SniffException;

/**
 * Sniff the layout type of a autogiro file
 *
 * @package itbz\swegiro
 */
class Sniffer implements LayoutInterface
{
    /**
     * Identifiers used when sniffing
     * 
     * @var array
     */
    private static $identifiers = array(
        self::LAYOUT_D => array(
            'first' => '/BET\. SPEC & STOPP TK/',
            'last'  => '//'
        ),
        self::LAYOUT_E => array(
            'first' => '/AUTOGIRO.*AG-MEDAVI/',
            'last'  => '//'
        ),
        self::LAYOUT_OLD_E => array(
            'first' => '/AG-MEDAVI/',
            'last'  => '//'
        ),
        self::LAYOUT_OLD_F => array(
            'first' => '/FELLISTA REG\.KONTRL/',
            'last'  => '//'
        ),
        self::LAYOUT_F => array(
            'first' => '/AVVISADE BET UPPDR/',
            'last'  => '//'
        ),
        self::LAYOUT_OLD_G => array(
            'first' => '/MAK\/ÄNDRINGSLISTA/',
            'last'  => '//'
        ),
        self::LAYOUT_G => array(
            'first' => '/MAKULERING\/ÄNDRING/',
            'last'  => '//'
        ),
        self::LAYOUT_H => array(
            'first' => '/AG-EMEDGIV/',
            'last'  => '//'
        ),
        self::LAYOUT_I => array(
            'first' => '/BEVAKNINGSREG/',
            'last'  => '//'
        ),
        self::LAYOUT_OLD_D => array(
            'first' => '/AUTOGIRO/',
            'last'  => '/^09/'
        ),
        self::LAYOUT_ABC => array(
            'first' => '/AUTOGIRO/',
            'last'  => '//'
        ),
        self::LAYOUT_J => array(
            'first' => '/^([0-9]|\s)+$/',
            'last'  => '/^([0-9]|\s)+$/'
        ),
        self::LAYOUT_BGMAX => array(
            'first' => '/BGMAX/',
            'last'  => '//'
        )
    );

    /**
     * Sniff the layout type of a autogiro file
     *
     * NOTE: The response is a *guess* and should not be depended upon.
     * 
     * @param array $lines The file contents
     * 
     * @return integer One of the LayoutInterface flags
     */
    public function sniff(array $lines)
    {
        $firstLine = current($lines);
        end($lines);
        $lastLine = current($lines);

        foreach (self::$identifiers as $flag => $regexes) {
            $firstLineRegex = utf8_decode($regexes['first']);
            $lastLineRegex = utf8_decode($regexes['last']);
            if (
                preg_match($firstLineRegex, $firstLine)
                && preg_match($lastLineRegex, $lastLine)
            ) {
                // Match found

                return $flag;
            }
        }

        $msg = _('Error sniffing file type: no matching type found.');
        throw new SniffException($msg);
    }
}
