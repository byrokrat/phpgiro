<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\autogiro\Strategy;

/**
 * Parser strategy for AG layout ABC
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class LayoutABC extends AbstractStrategy
{
    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function getRegexpMap()
    {
        return array(
            '/.*/' => 'voidParser'
        );
    }

    /**
     * Void parser
     *
     * @return void
     */
    public function voidParser()
    {
    }

    /**
     * Bogus getXML
     *
     * @return string
     */
    public function getXML()
    {
        return "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<autogiro version=\"0.1.0\"></autogiro>";
    }
}
