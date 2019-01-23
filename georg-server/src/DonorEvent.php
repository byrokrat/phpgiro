<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\georg;

use Symfony\Component\EventDispatcher\Event;
use ledgr\georg\Model\Donor;

/**
 * Donor event
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class DonorEvent extends Event
{
    /**
     * @var Donor Registered donor
     */
    private $donor;

    /**
     * Donor event
     *
     * @param Donor $donor
     */
    public function __construct(Donor $donor)
    {
        $this->donor = $donor;
    }

    /**
     * Get donor
     *
     * @return Donor
     */
    public function getDonor()
    {
        return $this->donor;
    }
}
