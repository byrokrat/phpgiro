<?php
namespace ledgr\autogiro\Builder;

use Mockery as m;

class ConsentBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testGetPerson()
    {
        $person = m::mock('ledgr\billing\LegalPerson');
        $org = m::mock('ledgr\billing\LegalPerson');
        $consent = new ConsentBuilder($person, $org);
        $this->assertSame($person, $consent->getPerson());
    }

    public function testGetRaw()
    {
        $person = m::mock('ledgr\billing\LegalPerson');
        $org = m::mock('ledgr\billing\LegalPerson');
        $consent = new ConsentBuilder($person, $org);

        $org->shouldReceive('getAccount')->andReturn(new \ledgr\banking\Bankgiro('111-1111'));

        $person->shouldReceive('getId')->andReturn(new \ledgr\id\PersonalId('010101-1112'));
        $person->shouldReceive('getAccount')->andReturn(new \ledgr\banking\NordeaPerson('3300,0101011112'));

        $this->assertEquals(80, strlen($consent->getRaw()));
    }

    public function testRejectedConsentGetRaw()
    {
        $person = m::mock('ledgr\billing\LegalPerson');
        $org = m::mock('ledgr\billing\LegalPerson');
        $consent = new RejectedConsentBuilder($person, $org);

        $org->shouldReceive('getAccount')->andReturn(new \ledgr\banking\Bankgiro('111-1111'));

        $person->shouldReceive('getId')->andReturn(new \ledgr\id\PersonalId('010101-1112'));
        $person->shouldReceive('getAccount')->andReturn(new \ledgr\banking\NordeaPerson('3300,0101011112'));

        $this->assertEquals(80, strlen($consent->getRaw()));
        $this->assertEquals('AV', substr($consent->getRaw(), 76, 2));
    }
}
