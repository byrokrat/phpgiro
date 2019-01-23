<?php
namespace ledgr\georg\Setup;

use Mockery as m;
use ledgr\id\CorporateId;
use ledgr\banking\Bankgiro;

class DbInstallerTest extends \PHPUnit_Framework_TestCase
{
    public function testInstall()
    {
        $manager = m::mock('ledgr\georg\Setup\DbManager');
        $manager->shouldReceive('createDatabase');
        $manager->shouldReceive('getDatabaseVersion')->andReturn('1');

        $io = m::mock('Composer\IO\IOInterface');
        $io->shouldReceive('write')->with('Georg database version 1');

        $installer = m::mock('ledgr\georg\Setup\DbInstaller', array($io, $manager))->makePartial();
        $installer->shouldReceive('askName')->andReturn('name');
        $installer->shouldReceive('askCorporateId')->andReturn(new CorporateId('777777-7777'));
        $installer->shouldReceive('askBankgiro')->andReturn(new Bankgiro('111-1111'));
        $installer->shouldReceive('askAutogiro')->andReturn('123456');

        $installer->install();
    }

    public function testUpdate()
    {
        $manager = m::mock('ledgr\georg\Setup\DbManager');
        $manager->shouldReceive('updateDatabase');
        $manager->shouldReceive('getDatabaseVersion')->andReturn('1');

        $io = m::mock('Composer\IO\IOInterface');
        $io->shouldReceive('write')->with('Georg database version 1');

        $installer = new DbInstaller($io, $manager);
        $installer->update();
    }

    public function testAskName()
    {
        $io = m::mock('Composer\IO\IOInterface');
        $io->shouldReceive('ask')->twice()->andReturn('', 'name');
        $io->shouldReceive('write')->once();

        $installer = new DbInstaller($io, m::mock('ledgr\georg\Setup\DbManager'));
        $this->assertEquals('name', $installer->askName());
    }

    public function testAskAutogiro()
    {
        $io = m::mock('Composer\IO\IOInterface');
        $io->shouldReceive('ask')->twice()->andReturn('', '123456');
        $io->shouldReceive('write')->once();

        $installer = new DbInstaller($io, m::mock('ledgr\georg\Setup\DbManager'));
        $this->assertEquals('123456', $installer->askAutogiro());
    }

    public function testAskCorporateId()
    {
        $io = m::mock('Composer\IO\IOInterface');
        $io->shouldReceive('ask')->twice()->andReturn('', '777777-7777');
        $io->shouldReceive('write')->once();

        $installer = new DbInstaller($io, m::mock('ledgr\georg\Setup\DbManager'));
        $this->assertEquals(new CorporateId('777777-7777'), $installer->askCorporateId());
    }

    public function testAskBankgiro()
    {
        $io = m::mock('Composer\IO\IOInterface');
        $io->shouldReceive('ask')->twice()->andReturn('', '111-1111');
        $io->shouldReceive('write')->once();

        $installer = new DbInstaller($io, m::mock('ledgr\georg\Setup\DbManager'));
        $this->assertEquals(new Bankgiro('111-1111'), $installer->askBankgiro());
    }
}
