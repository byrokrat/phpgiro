<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\georg\Model;

use ledgr\banking\Bankgiro;
use ledgr\id\CorporateId;
use Model;

/**
 * Settings model
 * 
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class Settings extends Model
{
    /**
     * @var string Name of database table
     */
    public static $_table = 'settings';

    /**
     * Get setting value
     *
     * @param  string $name Name of setting
     * @return string
     */
    public static function getSetting($name)
    {
        return self::factory('Settings')->where('name', $name)->find_one()->value;
    }

    /**
     * Get autogiro customer number
     *
     * @return string
     */
    public static function getAgCustomerNumber()
    {
        return self::getSetting('org_ag');
    }

    /**
     * Get bankgiro
     *
     * @return Bankgiro
     */
    public static function getBankgiro()
    {
        return new Bankgiro(self::getSetting('org_bg'));
    }

    /**
     * Get corporate id
     *
     * @return CorporateId
     */
    public static function getCorporateId()
    {
        return new CorporateId(self::getSetting('org_id'));
    }

    /**
     * Get name of organisation
     *
     * @return string
     */
    public static function getOrganisationName()
    {
        return self::getSetting('org_name');
    }

    /**
     * Get database version number
     *
     * @return string
     */
    public static function getVersion()
    {
        return self::getSetting('db_version');
    }
}
