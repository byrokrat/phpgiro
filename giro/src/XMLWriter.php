<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\giro;

/**
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class XMLWriter extends \XMLWriter
{
    /**
     * @var array Stack of active element names
     */
    private $activeStack = array();

    /**
     * XMLWriter extension
     */
    public function __construct()
    {
        $this->openMemory();
        $this->startDocument('1.0', 'UTF-8');
    }

    /**
     * Clear writer and start a fresh document
     *
     * @return void
     */
    public function clear()
    {
        $this->flush();
        $this->startDocument('1.0', 'UTF-8');
    }

    /**
     * Get generated xml
     *
     * @return string
     */
    public function getXml()
    {
        return $this->outputMemory(false);
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
     * @param  string $el name of element
     * @return bool   True on success, false on failure
     */
    public function startElement($el)
    {
        $this->activeStack[] = $el;
        return parent::startElement($el);
    }

    /**
     * End current element
     *
     * @return bool True on success, false on failure
     */
    public function endElement()
    {
        array_pop($this->activeStack);
        return parent::endElement();
    }
}
