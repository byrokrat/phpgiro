<?php

namespace byrokrat\autogiro\toBank;

use byrokrat\autogiro\FileObject;
use byrokrat\autogiro\Line;
use byrokrat\billing\LegalPerson;

/**
 * Base bgc file implementation
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
     * @var Record\Formatters Formatters collection
     */
    private $formatters;

    /**
     * Create bgc file
     *
     * @param LegalPerson       $creditor      Payment recipient
     * @param \DateTime         $date          Date of file creation
     * @param Record\Formatters $formatters    Formatters collection
     * @param FileObject        $fileObj       Bank file abstraction
     * @param Record            $openingRecord Opening record of file
     */
    public function __construct(
        LegalPerson $creditor,
        \DateTime $date = null,
        Record\Formatters $formatters = null,
        FileObject $fileObj = null,
        Record $openingRecord = null
    ) {
        $this->creditor = $creditor;
        $this->formatters = $formatters ?: new Record\Formatters;
        $this->fileObj = $fileObj ?: new FileObject;
        $this->addRecord(
            $openingRecord ?: new Record\OpeningRecord(
                $creditor,
                $date ?: new \DateTime,
                $this->formatters
            )
        );
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
     * Get formatters collection
     *
     * @return Record\Formatters
     */
    public function getFormatters()
    {
        return $this->formatters;
    }

    /**
     * Add record to file
     *
     * @param Record $record
     */
    public function addRecord(Record $record)
    {
        $this->fileObj->addLine(new Line($record->getRecord()));
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
}
