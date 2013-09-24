<?php
/**
 * This file is part of the swegiro package
 *
 * Copyright (c) 2012-13 Hannes Forsgård
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iio\swegiro;

use iio\swegiro\Parser\Parser;

/**
 * Abstract factory interface
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
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
     * Build validator for file type
     *
     * @return ValidatorInterface
     */
    public function createValidator();

    /**
     * Build parser for file tpye
     *
     * @param  integer           $flag One of the LayoutInterface flags
     * @return Parser
     * @throws StrategyException If flag is unknown
     */
    public function createParser($flag);
}
