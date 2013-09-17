<?php
namespace iio\swegiro\Builder;

use iio\swegiro\Organization;
use iio\stb\Banking\Bankgiro;

class AgBuilderTest extends \PHPUnit_Framework_TestCase
{
    private function getConvertToXMLBuilder()
    {
        $giro = $this->getMock(
            '\iio\swegiro\Swegiro',
            array('convertToXML'),
            array(),
            '',
            false
        );

        $giro->expects($this->once())
            ->method('convertToXML')
            ->will($this->returnValue(''));

        $org = new Organization();
        $org->setAgCustomerNumber('123456');
        $org->setBankgiro(new Bankgiro('111-1111'));

        return new AgBuilder($giro, $org);
    }

    public function testGetNative()
    {
        $builder = $this->getConvertToXMLBuilder();
        $builder->getNative();
    }

    public function testGetXML()
    {
        $builder = $this->getConvertToXMLBuilder();
        $builder->getXML();
    }

    public function testAddConsent()
    {
        $builder = $this->getConvertToXMLBuilder();

        $id = $this->getMock(
            'iio\swegiro\ID\PersonalId',
            array('getPayerNr', 'getFullIdNoDelimiter')
        );

        $id->expects($this->once())
            ->method('getPayerNr')
            ->will($this->returnValue('9999999999'));

        $id->expects($this->once())
            ->method('getFullIdNoDelimiter')
            ->will($this->returnValue('888888888888'));

        $account = $this->getMock(
            'iio\stb\Banking\FakeAccount',
            array('getClearing', 'getNumber'),
            array(),
            '',
            false
        );

        $account->expects($this->once())
            ->method('getClearing')
            ->will($this->returnValue('1111'));

        $account->expects($this->once())
            ->method('getNumber')
            ->will($this->returnValue('2222222'));

        $builder->addConsent($id, $account);

        // TODO när jag har skrivit om AgBuilder så att jag inte använder
        // PhpGiro längre så behöver jag bara testa att rätt delar av $id och $account
        // anropas. De andra delarna destas i getNative osv..
        $builder->getNative();
    }
}
