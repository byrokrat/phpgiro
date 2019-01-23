<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\georg\Model\properties;

/**
 * Defines getNotes() and setNotes() for georg models
 * 
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
trait Notes
{
    /**
     * Get registered notes
     *
     * @return string
     */
    public function getNotes()
    {
        return isset($this->notes) ? $this->notes : '';
    }

    /**
     * Register notes
     *
     * @param  string $notes
     * @return void
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
    }
}
