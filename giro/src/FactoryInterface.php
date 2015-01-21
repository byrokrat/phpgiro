<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\giro;

use ledgr\giro\Exception\FactoryException;

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
     * @throws FactoryException If unable to create sniffer
     */
    public function createSniffer();

    /**
     * Build validator for file type
     *
     * @param  mixed              $giroType Layout identifier
     * @return ValidatorInterface
     * @throws FactoryException   If unable to create validator
     */
    public function createValidator($giroType = '');

    /**
     * Build parser for file tpye
     *
     * @param  mixed            $giroType Layout identifier
     * @return Parser
     * @throws FactoryException If unable to create parser
     */
    public function createParser($giroType = '');
}
