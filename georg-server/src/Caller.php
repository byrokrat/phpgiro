<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\georg;

use inroute\Route;
use inroute\DefaultCaller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Create http request and response objects
 * 
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class Caller extends DefaultCaller
{
    /**
     * Call system controller
     *
     * @param  mixed $controller Anything acceptable by call_user_func
     * @param  Route $route
     * @return void
     */
    public function call($controller, Route $route)
    {
        $request = Request::createFromGlobals();
        $response = call_user_func($controller, $route, $request);
        $response->prepare($request);
        $response->send();
    }
}
