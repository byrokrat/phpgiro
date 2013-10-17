<?php
/**
 * This file is part of the swegiro package
 *
 * Copyright (c) 2012-13 Hannes Forsgård
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iio\swegiro\Factory;

use iio\swegiro\Sniffer\AgSniffer;
use iio\swegiro\Validator\DtdValidator;
use iio\swegiro\Parser\Parser;
use iio\swegiro\XMLWriter;

/**
 * Autogiro concrete factory
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class AgFactory implements FactoryInterface
{
    /**
     * {@inheritdoc}
     *
     * @return AgSniffer
     */
    public function createSniffer()
    {
        return new AgSniffer;
    }

    /**
     * {@inheritdoc}
     *
     * @return DtdValidator
     */
    public function createValidator()
    {
        $dtd = file_get_contents(
            implode(
                DIRECTORY_SEPARATOR,
                array(
                    __DIR__,
                    '..',
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
     * @param  string $classname LayoutInterface flag
     * @return Parser
     */
    public function createParser($classname)
    {
        return new Parser(new $classname(new XMLWriter));
    }
}
