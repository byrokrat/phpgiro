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
 * Defines getChanged() and setChanged() for georg models
 * 
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
trait Changed
{
    /**
     * Get changed time
     *
     * @return DateTime
     */
    public function getChanged()
    {
        return new DateTime($this->changed);
    }

    /**
     * Set changed time
     *
     * @param  DateTime $changed
     * @return void
     */
    public function setChanged(DateTime $changed = null)
    {
        $changed = $changed ?: new DateTime;
        $this->changed = $changed->format('Y-m-d H:i:s');
    }
}
