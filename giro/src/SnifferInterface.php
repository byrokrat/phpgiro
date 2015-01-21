<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\giro;

use ledgr\giro\Exception\SnifferException;

/**
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
interface SnifferInterface
{
    /**
     * Sniff the layout type of a giro file
     *
     * @param  array            $lines The file contents
     * @return scalar           Layout identifier
     * @throws SnifferException If sniff fails
     */
    public function sniffGiroType(array $lines);
}
