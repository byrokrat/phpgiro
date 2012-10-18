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

use itbz\swegiro\Parser\StrategyInterface;

/**
 * Abstract factory interface
 *
 * @package itbz\swegiro
 */
interface FactoryInterface extends LayoutInterface
{
    /**
     * Build sniffer
     *
     * @return SnifferInterface
     */
    public function createSniffer();

    /**
     * Build parsing strategy for file tpye
     *
     * @param integer $flag One of the LayoutInterface flags
     *
     * @return StrategyInterface
     *
     * @throws StrategyException If flag is unknown
     */
    public function createParserStrategy($flag);

    /**
     * Build validator for file type
     *
     * @param integer $flag One of the LayoutInterface flags
     *
     * @return ValidatorInterface
     *
     * @throws StrategyException If flag is unknown
     */
    public function createValidator($flag);
}
