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
 * @package itbz\phpautogiro\Parser
 */

namespace itbz\phpautogiro\Parser;

use itbz\Exception\ParserException;

/**
 * Interface for parsing AG files
 *
 * @package itbz\phpautogiro\Parser
 */
interface StrategyInterface
{
    /**
     * Reset internal state
     * 
     * @return void
     */
    public function clear();

    /**
     * Parse one line
     *
     * @param string $line
     *
     * @return void
     *
     * @throws ParserException if unable to parse line
     */
    public function parseLine($line);

    /**
     * Get created xml as a raw string
     *
     * @return string
     */
    public function getXml();
}
