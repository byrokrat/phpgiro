<?php
namespace iio\autogiro\Builder;

use iio\stb\Banking\Bankgiro;
use Mockery as m;

class AutogiroBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testGetXML()
    {
        $giro = m::mock('\iio\giro\Giro');
        $giro->shouldReceive('convertToXML')->once()->andReturn('<xml>');
        
        $org = m::mock('iio\autogiro\Builder\Organization');
        $org->shouldReceive('getAgCustomerNumber')->once();
        $org->shouldReceive('getBankgiro')->once()->andReturn(new Bankgiro('111-1111'));

        $converter = m::mock('iio\autogiro\Builder\AutogiroConverter');
        $converter->shouldReceive('convertBankgiro')->once();

        $builder = new AutogiroBuilder($giro, $org, $converter);
        $this->assertEquals('<xml>', $builder->getXML());
    }

    public function testAddConsent()
    {
        $giro = m::mock('\iio\giro\Giro');
        $giro->shouldReceive('convertToXML')->once();
        
        $org = m::mock('iio\autogiro\Builder\Organization');
        $org->shouldReceive('getAgCustomerNumber')->once();
        $org->shouldReceive('getBankgiro')->once()->andReturn(new Bankgiro('111-1111'));

        $converter = m::mock('iio\autogiro\Builder\AutogiroConverter');
        $converter->shouldReceive('convertBankgiro')->once()->andReturn('12341234');
        $converter->shouldReceive('convertPayerNr')->once()->andReturn('232323233');
        $converter->shouldReceive('convertId')->once()->andReturn('191963231234');

        $builder = new AutogiroBuilder($giro, $org, $converter);

        $id = m::mock('iio\stb\ID\PersonalId');
        
        $account = m::mock('iio\stb\Banking\AccountInterface');
        $account->shouldReceive('getClearing')->andReturn('1111');
        $account->shouldReceive('getNumber')->andReturn('2222222');

        $builder->addConsent($id, $account);
        $this->assertFalse($builder->getNative() == '');
    }

    public function testClear()
    {
        $giro = m::mock('\iio\giro\Giro');
        $giro->shouldReceive('convertToXML')->once();
        
        $org = m::mock('iio\autogiro\Builder\Organization');
        $org->shouldReceive('getAgCustomerNumber')->once();
        $org->shouldReceive('getBankgiro')->once()->andReturn(
            m::mock('iio\stb\Banking\Bankgiro')
        );

        $converter = m::mock('iio\autogiro\Builder\AutogiroConverter');
        $converter->shouldReceive('convertBankgiro')->once();

        $builder = new AutogiroBuilder($giro, $org, $converter);

        $builder->addConsent(
            m::mock('iio\stb\ID\PersonalId'),
            m::mock('iio\stb\Banking\AccountInterface')
        );

        $builder->clear();
        $this->assertTrue($builder->getNative() == '');
    }

    public function testBill()
    {
        $giro = m::mock('\iio\giro\Giro');
        $giro->shouldReceive('convertToXML')->once();
        
        $org = m::mock('iio\autogiro\Builder\Organization');
        $org->shouldReceive('getAgCustomerNumber')->once();
        $org->shouldReceive('getBankgiro')->once()->andReturn(new Bankgiro('111-1111'));

        $converter = m::mock('iio\autogiro\Builder\AutogiroConverter');
        $converter->shouldReceive('convertBankgiro')->once()->andReturn('1234');
        $converter->shouldReceive('convertPayerNr')->once()->andReturn('1234');

        $builder = new AutogiroBuilder($giro, $org, $converter);

        $id = m::mock('iio\stb\ID\PersonalId');
        
        $amount = m::mock('iio\stb\Utils\Amount');
        $amount->shouldReceive('__toString')->andReturn('999.99');

        $builder->bill($id, $amount, new \DateTime);
        $this->assertFalse($builder->getNative() == '');
    }
}
