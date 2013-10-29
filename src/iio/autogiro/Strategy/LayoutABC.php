<?php
/**
 * This file is part of the autogiro package
 *
 * Copyright (c) 2012-13 Hannes Forsgård
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iio\autogiro\Strategy;

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