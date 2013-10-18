<?php
/**
 * This file is part of the swegiro package
 *
 * Copyright (c) 2012-13 Hannes Forsgård
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iio\swegiro\Factory;

use iio\swegiro\Sniffer\SnifferInterface;
use iio\swegiro\Validator\ValidatorInterface;
use iio\swegiro\Parser\Parser;

/**
 * Abstract factory interface
 *
 * A factory is responsible for creating sniffer, validator and parser objects.
 * Each concrete factory represents a different giro system.
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
interface FactoryInterface
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
     * @param  scalar             $giroType Layout identifier
     * @return ValidatorInterface
     */
    public function createValidator($giroType = '');

    /**
     * Build parser for file tpye
     *
     * @param  scalar $giroType Layout identifier
     * @return Parser
     */
    public function createParser($giroType = '');
}
