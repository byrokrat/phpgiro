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

use Parser\Parser;
use Parser\Strategy\LayoutH;
use Exception\StrategyException;

/**
 * Create parsers for different AG file types
 *
 * @package itbz\phpautogiro
 */
class ParserFactory implements LayoutInterface
{
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
        assert('is_int($flag)');

        if ($flag == self::LAYOUT_H) {
            $strategy = new LayoutH;
        } else {
            $msg = _('Unable to create parsing strategy: layout unknown.');
            throw new StrategyException($msg);
        }

        return new Parser($strategy);
    }
}
