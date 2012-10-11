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

use itbz\phpautogiro\Parser\Parser;
use itbz\phpautogiro\Exception\StrategyException;

/**
 * Create parsers for different AG file types
 *
 * @package itbz\phpautogiro
 */
class ParserFactory implements LayoutInterface
{
    /**
     * Maps layout flags to class names
     * 
     * @var array
     */
    private static $classes = array(
        self::LAYOUT_H => 'itbz\phpautogiro\Parser\Strategy\LayoutH'
    );

    /**
     * Build a parser for AG file type denoted by flag
     *
     * @param int $flag One of the LayoutInterface flags
     *
     * @return Parser
     *
     * @throws StrategyException if flag is unknown
     */
    public function build($flag)
    {
        if (!isset(self::$classes[$flag])) {
            $msg = _('Unable to create parsing strategy: layout unknown.');
            throw new StrategyException($msg);
        }

        $strategyClass = self::$classes[$flag];

        return new Parser(new $strategyClass);
    }
}
