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

use iio\swegiro\FactoryInterface;
use iio\swegiro\Sniffer\AgSniffer;
use iio\swegiro\Validator\DtdValidator;
use iio\swegiro\Exception\StrategyException;
use iio\swegiro\Parser\Parser;
use iio\swegiro\XMLWriter;

/**
 * Autogiro factory class
 *
 * @author  Hannes Forsgård <hannes.forsgard@gmail.com>
 * @package swegiro
 */
class AgFactory implements FactoryInterface
{
    /**
     * @var array Maps layout flags to class names
     */
    private static $classes = array(
        self::LAYOUT_AG_H => 'iio\swegiro\Parser\Strategy\AG\LayoutH'
    );

    /**
     * {@inheritdoc}
     *
     * @return SnifferInterface
     */
    public function createSniffer()
    {
        return new AgSniffer;
    }

    /**
     * {@inheritdoc}
     *
     * @return ValidatorInterface
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
     * @param  integer           $flag One of the LayoutInterface flags
     * @return Parser
     * @throws StrategyException If flag is unknown
     */
    public function createParser($flag)
    {
        if (!isset(self::$classes[$flag])) {
            $msg = _('Unable to create parsing strategy: layout unknown.');
            throw new StrategyException($msg);
        }

        $strategyClass = self::$classes[$flag];

        return new Parser(new $strategyClass(new XMLWriter));
    }
}
