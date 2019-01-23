<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\georg\Log;

use Monolog\Logger;
use Monolog\Handler\AbstractProcessingHandler;

/**
 * Monolog PDO handler
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class DbHandler extends AbstractProcessingHandler
{
    /**
     * @var \PDOStatement Prepared statement
     */
    private $statement;

    /**
     * Monolog PDO handler
     *
     * @param \PDO    $pdo    
     * @param int     $level
     * @param boolean $bubble
     */
    public function __construct(\PDO $pdo, $level = Logger::DEBUG, $bubble = true)
    {
        $this->statement = $pdo->prepare(
            'INSERT INTO log (time, donor_id, level, message) VALUES (:time, :donorId, :level, :message)'
        );

        parent::__construct($level, $bubble);
    }

    /**
     * Write log record to database
     *
     * @param  array $record
     * @return void
     */
    protected function write(array $record)
    {
        $this->statement->execute(
            array(
                'time'    => $record['datetime']->format('Y-m-d H:i:s'),
                'donorId' => $record['context']['donorId'],
                'level'   => $record['level'],
                'message' => $record['formatted']
            )
        );
    }
}
