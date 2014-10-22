<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\autogiro\toBank;

use ledgr\autogiro\FileObject;
use ledgr\autogiro\Line;
use ledgr\billing\LegalPerson;

/**
 * Base BG file implementation
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class File
{
    /**
     * @var LegalPerson Payment recipient
     */
    private $creditor;

    /**
     * @var FileObject File to send to bank
     */
    private $fileObj;

    /**
     * Register payment recipient
     *
     * @param LegalPerson $creditor Payment recipient
     * @param FileObject  $fileObj  Bank file abstraction
     */
    public function __construct(LegalPerson $creditor, FileObject $fileObj = null)
    {
        // TODO update once LegalPerson is an interface in billing..
        $this->creditor = $creditor;
        $this->fileObj = $fileObj ?: $this->createFileObject($creditor);
    }

    /**
     * Get creditor registered with this file
     *
     * @return LegalPerson
     */
    public function getCreditor()
    {
        return $this->creditor;
    }

    /**
     * Get file contents
     *
     * @return string
     */
    public function getContents()
    {
        return $this->fileObj->getContents();
    }

    /**
     * Add line to section
     *
     * @param string $line
     */
    public function addLine($line)
    {
        $this->fileObj->addLine(new Line($line));
    }

    /**
     * Create new file object
     *
     * @param  LegalPerson $creditor Payment recipient
     * @return FileObject
     */
    private function createFileObject(LegalPerson $creditor)
    {
        $fileObj = new FileObject;
        // TODO use $creditor->getAccount()->format() once banking is at 2.0
        $fileObj->addLine(
            new Line(
                '01'
                . (new \DateTime)->format('Ymd')
                . 'AUTOGIRO'
                . str_repeat(' ', 44)
                . str_pad($creditor->getCustomerNumber(), 6, '0', STR_PAD_LEFT)
                . str_pad(str_replace('-', '', $creditor->getAccount()), 10, '0', STR_PAD_LEFT)
                . str_repeat(' ', 2)
            )
        );
        return $fileObj;
    }
}
