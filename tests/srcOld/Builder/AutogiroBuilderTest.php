<?php
namespace ledgr\autogiro\Builder;

use ledgr\banking\Bankgiro;
use Mockery as m;

class AutogiroBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testGetXML()
    {
        $org = m::mock('ledgr\billing\LegalPerson');
        $org->shouldReceive('getCustomerNumber')->once();
        $org->shouldReceive('getAccount')->once()->andReturn(new Bankgiro('111-1111'));

        $giro = m::mock('\ledgr\giro\Giro');
        $giro->shouldReceive('convertToXML')->once()->andReturn('<xml>');

        $converter = m::mock('ledgr\autogiro\Builder\AutogiroConverter');
        $converter->shouldReceive('convertBankgiro')->once();

        $builder = new AutogiroBuilder($org, $giro, $converter);
        $this->assertEquals('<xml>', $builder->getXML());
    }

    public function testClear()
    {
        $org = m::mock('ledgr\billing\LegalPerson');
        $org->shouldReceive('getCustomerNumber')->once();
        $org->shouldReceive('getAccount')->once()->andReturn(
            m::mock('ledgr\banking\Bankgiro')
        );

        $giro = m::mock('\ledgr\giro\Giro');
        $giro->shouldReceive('convertToXML')->once();

        $converter = m::mock('ledgr\autogiro\Builder\AutogiroConverter');
        $converter->shouldReceive('convertBankgiro')->once();

        $builder = new AutogiroBuilder($org, $giro, $converter);

        $builder->addConsent(m::mock('ledgr\billing\LegalPerson'));

        $builder->clear();
        $this->assertTrue($builder->getNative() == '');
    }

    public function testAddConsent()
    {
        $org = m::mock('ledgr\billing\LegalPerson');
        $org->shouldReceive('getCustomerNumber')->once();
        $org->shouldReceive('getAccount')->once()->andReturn(new Bankgiro('111-1111'));

        $giro = m::mock('\ledgr\giro\Giro');
        $giro->shouldReceive('convertToXML')->once();

        $converter = m::mock('ledgr\autogiro\Builder\AutogiroConverter');
        $converter->shouldReceive('convertBankgiro')->once()->andReturn('12341234');
        $converter->shouldReceive('convertPayerNr')->once()->andReturn('232323233');
        $converter->shouldReceive('convertId')->once()->andReturn('191963231234');

        $builder = new AutogiroBuilder($org, $giro, $converter);

        $person = m::mock('ledgr\billing\LegalPerson');
        $person->shouldReceive('getId')->andReturn(m::mock('ledgr\id\PersonalId'));
        $person->shouldReceive('getAccount')->andReturn(new Bankgiro('111-1111'));

        $builder->addConsent($person);
        $this->assertFalse($builder->getNative() == '');
    }

    public function testAddInvoice()
    {
        $org = m::mock('ledgr\billing\LegalPerson');
        $org->shouldReceive('getCustomerNumber')->once();
        $org->shouldReceive('getAccount')->once()->andReturn(new Bankgiro('111-1111'));

        $giro = m::mock('\ledgr\giro\Giro');
        $giro->shouldReceive('convertToXML')->once();

        $converter = m::mock('ledgr\autogiro\Builder\AutogiroConverter');
        $converter->shouldReceive('convertBankgiro')->once()->andReturn('1234');
        $converter->shouldReceive('convertPayerNr')->once()->andReturn('1234');

        $builder = new AutogiroBuilder($org, $giro, $converter);

        $invoice = m::mock('ledgr\billing\Invoice');
        $invoice->shouldReceive('getBuyer->getId')->andReturn(m::mock('ledgr\id\PersonalId'));
        $invoice->shouldReceive('getInvoiceTotal->__toString')->andReturn('999.99');
        $invoice->shouldReceive('getExpirationDate->format')->andReturn('19820323');

        $builder->addInvoice($invoice);
        $this->assertFalse($builder->getNative() == '');
    }
}
