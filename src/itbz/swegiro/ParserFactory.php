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

use itbz\swegiro\Parser\Parser;
use itbz\swegiro\Exception\StrategyException;
use itbz\swegiro\Validator\DtdValidator;

/**
 * Create parsers for different AG file types
 *
 * @package itbz\swegiro
 */
class ParserFactory implements LayoutInterface
{
    /**
     * Maps layout flags to class names
     * 
     * @var array
     */
    private static $classes = array(
        self::LAYOUT_AG_H => 'itbz\swegiro\Parser\Strategy\AG\LayoutH'
    );

    /**
     * Name of DTD used
     *
     * NOTE: Could be dynamically choosen depending on strategy. This is a
     * simple solution.
     */
    const DTD_NAME = 'autogiro.dtd';

    /**
     * Build a parser for AG file type denoted by flag
     *
     * NOTE: The project basedir must be added to include path in order for
     * DTD to be auto loaded
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

        $dtd = file_get_contents(
            __DIR__
            . DIRECTORY_SEPARATOR
            . 'DTD'
            . DIRECTORY_SEPARATOR
            . self::DTD_NAME
        );

        // TODO root node name should not be hardcoded here
        $validator = new DtdValidator('autogiro', $dtd);

        return new Parser(new $strategyClass, $validator);
    }
}
