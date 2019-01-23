<?php
namespace ledgr\georg\Setup;

use ledgr\id\CorporateId;
use ledgr\banking\Bankgiro;

class DbManagerTest extends \PHPUnit_Framework_TestCase
{
    private function getManager()
    {
        $pdo = new \PDO('sqlite::memory:');
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return new DbManager($pdo);
    }

    public function testDatabaseDoesNotExists()
    {
        $manager = $this->getManager();
        $this->assertFalse($manager->databaseExists());
    }

    public function testCreateDatabase()
    {
        $manager = $this->getManager();
        $manager->createDatabase('org', new CorporateId('777777-7777'), new Bankgiro('1234-1236'), '123');
        $this->assertTrue($manager->databaseExists());
    }

    /**
     * @expectedException \PDOException
     */
    public function testCreateException()
    {
        $manager = $this->getManager();
        $manager->createDatabase('org', new CorporateId('777777-7777'), new Bankgiro('1234-1236'), '123');
        // Create when database exists should throw exception
        $manager->createDatabase('org', new CorporateId('777777-7777'), new Bankgiro('1234-1236'), '123');
    }
}
