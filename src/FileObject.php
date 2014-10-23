<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\autogiro;

/**
 * File access object
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class FileObject implements \Countable, \IteratorAggregate
{
    /**
     * @var Line[] Contained lines
     */
    private $lines = [];

    /**
     * Create FileObject from raw data
     *
     * @param string $data
     */
    public function __construct($data = '')
    {
        if ($data) {
            foreach (preg_split("/(\r\n|\n|\r)/", $data) as $raw) {
                $this->addLine(new Line($raw));
            }
        }
    }

    /**
     * Add a line to file
     *
     * @param Line $line
     */
    public function addLine(Line $line)
    {
        $this->lines[] = $line;
    }

    /**
     * Get contents
     *
     * @param  string $eol      End of line character(s) used
     * @param  string $encoding Encoding used
     * @return string
     */
    public function getContents($eol = "\r\n", $encoding = 'ISO-8859-1')
    {
        return array_reduce(
            $this->lines,
            function ($carry, $line) use ($eol, $encoding) {
                return $carry . $line->convertTo($encoding) . $eol;
            },
            ''
        );
    }

    /**
     * Get line from content
     *
     * @param  int $lineNumber
     * @return Line
     * @throws Exception\RuntimeException If line does not exist
     */
    public function getLine($lineNumber)
    {
        if (isset($this->lines[$lineNumber])) {
            return $this->lines[$lineNumber];
        }

        throw new Exception\RuntimeException("Line <$lineNumber> does not exist");
    }

    /**
     * Get first line that has content
     *
     * @return Line
     * @throws Exception\RuntimeException If no line with content exists
     */
    public function getFirstLine()
    {
        foreach ($this->lines as $line) {
            if (!$line->isEmpty()) {
                return $line;
            }
        }

        throw new Exception\RuntimeException("Non-empty line not found");
    }

    /**
     * Get the last line that has content
     *
     * @return Line
     * @throws Exception\RuntimeException If no line with content exists
     */
    public function getLastLine()
    {
        foreach (array_reverse($this->lines) as $line) {
            if (!$line->isEmpty()) {
                return $line;
            }
        }

        throw new Exception\RuntimeException("Non-empty line not found");
    }

    /**
     * Count the number of lines in file
     *
     * @return int
     */
    public function count()
    {
        return count($this->lines);
    }

    /**
     * Iterate over lines
     *
     * @return \Traversable
     */
    public function getIterator()
    {
        foreach ($this->lines as $lineNumber => $line) {
            yield $lineNumber => $line;
        }
    }
}
