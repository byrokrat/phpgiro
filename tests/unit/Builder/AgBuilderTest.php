<?php
namespace iio\swegiro\Builder;

use iio\swegiro\Organization;
use iio\stb\Banking\Bankgiro;

class AgBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testAddConsent()
    {
        $giro = $this->getMock(
            '\iio\swegiro\Swegiro',
            array(),
            array(),
            '',
            false
        );

        $org = new Organization();
        $org->setAgCustomerNumber('666666');
        $org->setBankgiro(new Bankgiro('123-1232'));

        $a = new AgBuilder($giro, $org);

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

        $a->addConsent($id, $account);

        $a->getNative();
    }

    public function testGetXML()
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

        $builder = new AgBuilder($giro, new Organization());
        $builder->getXML();
    }
}
