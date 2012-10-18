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
 * @package itbz\swegiro\Parser\Strategy\AG
 */

namespace itbz\swegiro\Parser\Strategy\AG;

use itbz\swegiro\Parser\StrategyInterface;
use itbz\swegiro\Exception\ParserException;

/**
 * Parser for AG layout H (new electronic consents)
 *
 * @package itbz\swegiro\Parser\Strategy\AG
 */
class LayoutH implements StrategyInterface
{
    /**
     * {@inheritdoc}
     * 
     * @return void
     */
    public function clear()
    {
    }

    /**
     * {@inheritdoc}
     * 
     * @param string $line
     * @return void
     * @throws ParserException If unable to parse line
     */
    public function parseLine($line)
    {
    }

    /**
     * {@inheritdoc}
     * 
     * @return string
     */
    public function getXml()
    {
    }
}
