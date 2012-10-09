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

use itbz\phpautogiro\Exception\SniffException;

/**
 * Sniff the layout type of a autogiro file
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
        self::LAYOUT_NEW_D => array(
            'first' => '/BET\. SPEC & STOPP TK/',
            'last'  => '//'
        ),
        self::LAYOUT_NEW_E => array(
            'first' => '/AUTOGIRO.*AG-MEDAVI/',
            'last'  => '//'
        ),
        self::LAYOUT_AGP_E => array(
            'first' => '/AG-MEDAVI/',
            'last'  => '//'
        ),
        self::LAYOUT_AGP_F => array(
            'first' => '/FELLISTA REG\.KONTRL/',
            'last'  => '//'
        ),
        self::LAYOUT_NEW_F => array(
            'first' => '/AVVISADE BET UPPDR/',
            'last'  => '//'
        ),
        self::LAYOUT_AGP_G => array(
            'first' => '/MAK\/ÄNDRINGSLISTA/',
            'last'  => '//'
        ),
        self::LAYOUT_NEW_G => array(
            'first' => '/MAKULERING\/ÄNDRING/',
            'last'  => '//'
        ),
        self::LAYOUT_AGP_H => array(
            'first' => '/AG-EMEDGIV/',
            'last'  => '//'
        ),
        self::LAYOUT_NEW_I => array(
            'first' => '/BEVAKNINGSREG/',
            'last'  => '//'
        ),
        self::LAYOUT_AGP_D => array(
            'first' => '/AUTOGIRO/',
            'last'  => '/^09/'
        ),
        self::LAYOUT_AGP_ABC => array(
            'first' => '/AUTOGIRO/',
            'last'  => '//'
        ),
        self::LAYOUT_NEW_J => array(
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
        // Remove empty lines
        $lines = array_filter(
            $lines,
            function ($line) {
                return !!trim($line);
            }
        );

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
