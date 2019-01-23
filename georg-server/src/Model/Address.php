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
 * Address model
 * 
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 * @todo   Använd address value class (phplibaddress)
 */
class Address extends DonorAssociation
{
    use properties\Changed, properties\Notes;

    /**
     * @var string Name of database table
     */
    public static $_table = 'address';

    // TODO implement getters and setters
}
