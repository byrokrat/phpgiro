<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\georg\Log;

use Monolog\Handler\AbstractProcessingHandler;

/**
 * Keep logged data for the duration of the request
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class ReturnHandler extends AbstractProcessingHandler
{
    /**
     * @var array Logged records
     */
    private $records = array();

    /**
     * Write log record to database
     *
     * @param  array $record
     * @return void
     */
    protected function write(array $record)
    {
        $this->records[] = $record;
    }

    /**
     * Get prettyprinted logg records
     *
     * @return array
     */
    public function getRecords()
    {
        return array_map(
            function ($record) {
                return "{$record['context']['donorId']}: {$record['message']}";
            },
            $this->records
        );
    }
}
