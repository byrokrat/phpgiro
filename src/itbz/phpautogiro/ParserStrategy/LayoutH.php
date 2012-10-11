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
 * @package itbz\phpautogiro\ParserStrategy
 */

namespace itbz\phpautogiro\ParserStrategy;

use itbz\phpautogiro\ParserStrategyInterface;
use itbz\phpautogiro\Exception\ParserException;

/**
 * Parser for AG layout H (new electronic consents)
 *
 * @package itbz\phpautogiro\ParserStrategy
 */
class LayoutH implements ParserStrategyInterface
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
    public function parse($line)
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
