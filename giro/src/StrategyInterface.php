<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\giro;

/**
 * Interface for a parsing strategy
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
interface StrategyInterface
{
    /**
     * Get array of regular expressions maped to parser methods
     *
     * A parser method must be a public method of the stretegy, and should take
     * as many arguments as captured by the mapped regular expression.
     * 
     * @return array
     */
    public function getRegexpMap();

    /**
     * Reset internal state
     * 
     * @return void
     */
    public function clear();

    /**
     * Get created xml as a raw string
     *
     * @return string
     */
    public function getXML();
}
