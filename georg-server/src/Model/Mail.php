<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\georg\Model;

/**
 * Mail model
 * 
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class Mail extends DonorAssociation
{
    use properties\Changed, properties\Notes;

    /**
     * @var string Name of database table
     */
    public static $_table = 'mail';

    /**
     * Get mail address
     *
     * @return string
     */
    public function getMail()
    {
        return isset($this->mail) ? $this->mail : '';
    }

    /**
     * Set mail address
     *
     * @param  string $mail
     * @return void
     * @todo   Använd någon form av data value class (så att mail adresser valideras!!)
     */
    public function setMail($mail)
    {
        $this->mail = $mail;
    }
}
