<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\autogiro;

use ledgr\giro\FactoryInterface;
use ledgr\giro\DtdValidator;
use ledgr\giro\Parser;
use ledgr\giro\XMLWriter;

/**
 * Autogiro concrete factory
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class AutogiroFactory implements FactoryInterface, Layouts
{
    /**
     * {@inheritdoc}
     *
     * @return Sniffer
     */
    public function createSniffer()
    {
        return new Sniffer;
    }

    /**
     * {@inheritdoc}
     *
     * @param  scalar       $giroType Layout identifier
     * @return DtdValidator
     */
    public function createValidator($giroType = '')
    {
        $dtd = file_get_contents(
            implode(
                DIRECTORY_SEPARATOR,
                array(
                    __DIR__,
                    'DTD',
                    'autogiro.dtd'
                )
            )
        );

        return new DtdValidator('autogiro', $dtd);
    }

    /**
     * {@inheritdoc}
     *
     * @param  scalar $giroType Layout identifier
     * @return Parser
     */
    public function createParser($giroType = '')
    {
        return new Parser(new $giroType(new XMLWriter));
    }
}
