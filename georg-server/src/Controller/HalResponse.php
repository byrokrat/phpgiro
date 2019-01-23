<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\georg\Controller;

use Symfony\Component\HttpFoundation\Response;
use ledgr\georg\Model\HalSerializable;

/**
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 * @todo   Ska inte vara i controller namespace??
 * @todo   HalSerializable är ett misstag, ett utomstående objekt ska göra detta
 *         Ett objekt har naturligt inte kunskap om relaterade länkar
 */
class HalResponse extends Response
{
    /**
     * A HalResponse encasulates a HalSerializable
     *
     * @param string          $url          Url to self
     * @param HalSerializable $resource     Resource to send
     * @param integer         $responseCode Http response code (defaults to 200)
     * @param array           $headers      Http headers (defaults to content-type: application/json)
     */
    public function __construct($url, HalSerializable $resource, $responseCode = 200, array $headers = [])
    {
        $headers = $headers ?: ['content-type' => 'application/json'];
        parent::__construct($resource->halSerialize($url), $responseCode, $headers);
    }
}
