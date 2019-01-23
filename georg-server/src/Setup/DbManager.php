<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\georg\Setup;

use PDO;
use PDOException;
use ledgr\id\CorporateId;
use ledgr\banking\Bankgiro;

/**
 * Create and update the database schema
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class DbManager
{
    /**
     * @var PDO Database handle
     */
    private $pdo;

    /**
     * Inject PDO instance
     *
     * @param PDO $pdo Expects a sqlite3 database. It is recommended to use
     * PDO::ERRMODE_EXCEPTION to notice unexpected behavior. 
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Return current database version
     *
     * @return string Empty string if no version number exists
     */
    public function getDatabaseVersion()
    {
        try {
            $stmt = $this->pdo->query("SELECT value FROM settings WHERE name='db_version'");
            if ($stmt !== false) {
                return $stmt->fetchColumn(0);
            }
        } catch (PDOException $e) {
        }

        return '';
    }

    /**
     * Check if database handle is a georg database
     *
     * @return boolean
     */
    public function databaseExists()
    {
        return $this->getDatabaseVersion() !== '';
    }

    /**
     * Create a complete and updated database
     *
     * @param  string      $name Name of organization
     * @param  CorporateId $id   Organization state id
     * @param  Bankgiro    $bg   Organization bg account number
     * @param  string      $ag   Organization ag customer number
     * @return void
     */
    public function createDatabase($name, CorporateId $id, Bankgiro $bg, $ag)
    {
        $this->createBasicDatabase($name, $id, $bg, $ag);
        $this->updateDatabase();
    }

    /**
     * Update database schema
     *
     * @return void
     */
    public function updateDatabase()
    {
        $updates =  [
            '0.1.1' => [
                // ["UPDATE settings SET value='0.1.1' WHERE name='db_version'", []]
            ]
        ];

        foreach ($updates as $version => $queries) {
            if (version_compare($this->getDatabaseVersion(), $version) === -1) {
                $this->runQueries($queries);
            }
        }
    }

    /**
     * Create the basic database schema
     *
     * @param  string      $name Name of organization
     * @param  CorporateId $id   Organization state id
     * @param  Bankgiro    $bg   Organization bg account number
     * @param  string      $ag   Organization ag customer number
     * @return void
     */
    private function createBasicDatabase($name, CorporateId $id, Bankgiro $bg, $ag)
    {
        assert('is_string($name)');
        assert('is_string($ag)');

        $queries = [
            ["CREATE TABLE settings(id INTEGER PRIMARY KEY AUTOINCREMENT, name, value)", []],
            ["INSERT INTO settings (name, value) VALUES ('db_version', '0.1.0')", []],
            ["INSERT INTO settings (name, value) VALUES ('org_name', ?)", [$name]],
            ["INSERT INTO settings (name, value) VALUES ('org_id', ?)", [(string) $id]],
            ["INSERT INTO settings (name, value) VALUES ('org_bg', ?)", [(string) $bg]],
            ["INSERT INTO settings (name, value) VALUES ('org_ag', ?)", [$ag]],

            ["CREATE TABLE donor (
                id PRIMARY KEY,
                created DEFAULT CURRENT_TIMESTAMP,
                changed DEFAULT CURRENT_TIMESTAMP,
                given_name,
                surname,
                account,
                amount DEFAULT 0,
                payment_term,
                notes,
                current_total DEFAULT 0
            )", []],

            ["CREATE TABLE mail (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                changed DEFAULT CURRENT_TIMESTAMP,
                donor_id,
                mail,
                notes,
                FOREIGN KEY(donor_id) REFERENCES donor(id)
            )", []],

            ["CREATE TABLE address (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                changed DEFAULT CURRENT_TIMESTAMP,
                donor_id,
                street,
                plot,
                postcode,
                town,
                country DEFAULT 'se',
                notes,
                FOREIGN KEY(donor_id) REFERENCES donor(id)
            )", []],

            ["CREATE TABLE `transaction` (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                date,
                donor_id,
                file_id,
                amount,
                response_code,
                FOREIGN KEY(donor_id) REFERENCES donor(id),
                FOREIGN KEY(file_id) REFERENCES file(id)
            )", []],

            ["CREATE TABLE log (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                time DEFAULT CURRENT_TIMESTAMP,
                donor_id,
                level,
                message,
                FOREIGN KEY(donor_id) REFERENCES donor(id)
            )", []],
        ];

        $this->runQueries($queries);
    }

    /**
     * Run a collection of queries
     *
     * @param  array $queries
     * @return void
     */
    private function runQueries(array $queries)
    {
        $this->pdo->beginTransaction();
        foreach ($queries as $data) {
            list ($query, $values) = $data;
            try {
                $stmt = $this->pdo->prepare($query);
                $stmt->execute($values);
            } catch (PDOException $e) {
                $this->pdo->rollBack();
                throw $e;
            }
        }
        $this->pdo->commit();
    }
}
