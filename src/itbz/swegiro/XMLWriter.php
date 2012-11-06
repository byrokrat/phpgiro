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
 * @package itbz\swegiro
 */

namespace itbz\swegiro;

/**
 * XMLWriter extension
 *
 * @package itbz\swegiro
 */
class XMLWriter extends \XMLWriter
{
    /**
     * Stack of active element names
     *
     * @var array
     */
    private $activeStack = array();

    /**
     * Clear writer and start a fresh document
     *
     * @return void
     *
     * @todo reset writer så att jag kan börja ett nytt dokument
     */
    public function clear()
    {
        // såhär såg clear i Strategy ut förut...
        // $this->xmlWriter = new XMLWriter;
        // $this->xmlWriter->openMemory();
        // $this->xmlWriter->startDocument('1.0', 'UTF-8');
    }

    /**
     * Get name of current top element
     *
     * @return string
     */
    public function currentElement()
    {
        if (empty($this->activeStack)) {
            return '';
        }

        end($this->activeStack);

        return current($this->activeStack);
    }

    /**
     * Start a new element
     *
     * @param string $el name of element
     *
     * @return ??????
     */
    public function startElement($el)
    {
        $this->activeStack[] = $el;
        return parent::startElement($el);
    }

    /**
     * End current element
     *
     * @return ????
     */
    public function endElement()
    {
        array_pop($this->activeStack);
        return parent::endElement();
    }
}
