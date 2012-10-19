<?php
/**
 * This file is part of the swegiro package
 *
 * Copyright (c) 2012 Hannes Forsgård
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Hannes Forsgård <hannes.forsgard@gmail.com>
 * @package itbz\swegiro\Factory;
 */

namespace itbz\swegiro\Factory;

use itbz\swegiro\FactoryInterface;
use itbz\swegiro\Sniffer\AgSniffer;
use itbz\swegiro\Validator\DtdValidator;
use itbz\swegiro\Exception\StrategyException;

/**
 * Autogiro factory class
 *
 * @package itbz\swegiro\Factory
 */
class AgFactory implements FactoryInterface
{
    /**
     * Maps layout flags to class names
     * 
     * @var array
     */
    private static $classes = array(
        self::LAYOUT_AG_H => 'itbz\swegiro\Parser\Strategy\AG\LayoutH'
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
     * @param integer $flag One of the LayoutInterface flags
     *
     * @return StrategyInterface
     *
     * @throws StrategyException If flag is unknown
     */
    public function createParserStrategy($flag)
    {
        if (!isset(self::$classes[$flag])) {
            $msg = _('Unable to create parsing strategy: layout unknown.');
            throw new StrategyException($msg);
        }

        $strategyClass = self::$classes[$flag];

        return new $strategyClass;
    }
}
