<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\georg\Setup;

use ledgr\id\CorporateId;
use ledgr\id\Exception as IdException;
use ledgr\banking\Bankgiro;
use ledgr\banking\Exception as BankingException;
use Composer\IO\IOInterface;

/**
 * Georg database installer
 * 
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class DbInstaller
{
    /**
     * @var IOInterface Conposer IO
     */
    private $io;

    /**
     * @var DbManager Georg database manager
     */
    private $dbManager;

    /**
     * Georg database installer
     *
     * @param  IOInterface $io
     * @param  DbManager   $dbManager
     * @return void
     */
    public function __construct(IOInterface $io, DbManager $dbManager)
    {
        $this->io = $io;
        $this->dbManager = $dbManager;
    }

    /**
     * Create database
     *
     * @return void
     */
    public function install()
    {
        $this->dbManager->createDatabase(
            $this->askName(),
            $this->askCorporateId(),
            $this->askBankgiro(),
            $this->askAutogiro()
        );

        $this->io->write(
            'Georg database version '
            . $this->dbManager->getDatabaseVersion()
        );
    }

    /**
     * Update database
     *
     * @return void
     */
    public function update()
    {
        $this->dbManager->updateDatabase();

        $this->io->write(
            'Georg database version '
            . $this->dbManager->getDatabaseVersion()
        );
    }

    /**
     * Read organisation name from IO
     *
     * @return string
     */
    public function askName()
    {
        $name = $this->io->ask('(ge.org) Organisationens namn: ', '');
        if (!$name) {
            $this->io->write("\n(ge.org) ERROR Namn måste anges");
            return $this->askName();
        }

        return $name;
    }

    /**
     * Read corporate id from IO
     *
     * @return CorporateId
     */
    public function askCorporateId()
    {
        try {
            return new CorporateId(
                $this->io->ask('(ge.org) Organisatiosnummer: ', '')
            );
        } catch (IdException $e) {
            $this->io->write("\n(ge.org) ERROR " . $e->getMessage());
            return $this->askCorporateId();
        }
    }

    /**
     * Read bankgiro from IO
     *
     * @return Bankgiro
     */
    public function askBankgiro()
    {
        try {
            return new Bankgiro(
                $this->io->ask('(ge.org) Bankgironummer: ', '')
            );
        } catch (BankingException $e) {
            $this->io->write("\n(ge.org) ERROR " . $e->getMessage());
            return $this->askBankgiro();
        }
    }

    /**
     * Read autogiro customer number from IO
     *
     * @return string
     */
    public function askAutogiro()
    {
        $ag = $this->io->ask('(ge.org) Kundnummer för autogirot: ', '');
        if (!ctype_digit($ag) or !(strlen($ag) == 6)) {
            $this->io->write("\n(ge.org) ERROR Kundnummer måste bestå av 6 siffor");
            return $this->askAutogiro();
        }

        return $ag;
    }
}
