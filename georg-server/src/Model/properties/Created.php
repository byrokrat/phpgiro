<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\georg\Model\properties;

use DateTime;

/**
 * Defines getCreated() and setCreated() for georg models
 * 
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
trait Created
{
    /**
     * Get creation time
     *
     * @return DateTime
     */
    public function getCreated()
    {
        return new DateTime($this->created);
    }

    /**
     * Set creation time
     *
     * @param  DateTime $created
     * @return void
     */
    public function setCreated(DateTime $created = null)
    {
        $created = $created ?: new DateTime;
        $this->created = $created->format('Y-m-d H:i:s');
    }
}
