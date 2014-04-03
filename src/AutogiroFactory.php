<?php
/**
 * This file is part of the autogiro package
 *
 * Copyright (c) 2012-13 Hannes Forsgård
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
