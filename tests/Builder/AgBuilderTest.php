<?php
namespace iio\swegiro\Builder;

use iio\stb\Banking\Bankgiro;
use Mockery as m;

class AgBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testGetXML()
    {
        $giro = m::mock('\iio\swegiro\Swegiro');
        $giro->shouldReceive('convertToXML')->once()->andReturn('<xml>');
        
        $org = m::mock('iio\swegiro\Organization');
        $org->shouldReceive('getAgCustomerNumber')->once();
        $org->shouldReceive('getBankgiro')->once()->andReturn(new Bankgiro('111-1111'));

        $builder = new AgBuilder($giro, $org);
        $this->assertEquals('<xml>', $builder->getXML());
    }

    public function testAddConsent()
    {
        $giro = m::mock('\iio\swegiro\Swegiro');
        $giro->shouldReceive('convertToXML')->once();
        
        $org = m::mock('iio\swegiro\Organization');
        $org->shouldReceive('getAgCustomerNumber')->once();
        $org->shouldReceive('getBankgiro')->once()->andReturn(new Bankgiro('111-1111'));

        $builder = new AgBuilder($giro, $org);

        $id = m::mock('iio\swegiro\ID\PersonalId');
        $id->shouldReceive('getPayerNr')->once();
        $id->shouldReceive('getFullIdNoDelimiter')->once();
        
        $account = m::mock('iio\stb\Banking\AccountInterface');
        $account->shouldReceive('getClearing')->andReturn('1111');
        $account->shouldReceive('getNumber')->andReturn('2222222');

        $builder->addConsent($id, $account);
        $this->assertFalse($builder->getNative() == '');
    }
}
