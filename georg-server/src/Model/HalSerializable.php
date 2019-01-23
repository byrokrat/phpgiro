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
 * Defines HAL aware objects
 * 
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 * @todo   HalSerializable är ett misstag, ett utomstående objekt ska göra detta
 *         Ett objekt har naturligt inte kunskap om relaterade länkar
 */
interface HalSerializable
{
    /**
     * Get HAL resource with object data
     *
     * @param  string $url   Url to resource
     * @param  \Hal\Link     $links Additional resource links
     * @return \Hal\Resource
     */
    public function halSerialize($url, array $links = []);
}
