<?php
namespace iio\swegiro;

use iio\stb\Banking\PlusGiro;
use iio\stb\Banking\Bankgiro;

class OrganizationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException iio\swegiro\Exception
     */
    public function testNoBankgiroException()
    {
        $o = new Organization();
        $o->getBankgiro();
    }

    /**
     * @expectedException iio\swegiro\Exception
     */
    public function testNoPlusgiroException()
    {
        $o = new Organization();
        $o->getPlusgiro();
    }

    /**
     * @expectedException iio\swegiro\Exception
     */
    public function testNoNameException()
    {
        $o = new Organization();
        $o->getName();
    }

    /**
     * @expectedException iio\swegiro\Exception
     */
    public function testNoAgCustomerNumberException()
    {
        $o = new Organization();
        $o->getAgCustomerNumber();
    }

    public function testBankgiro()
    {
        $o = new Organization();
        $o->setBankgiro(new Bankgiro('111-1111'));
        $this->assertEquals(
            '111-1111',
            (string)$o->getBankgiro()
        );
    }

    public function testPlusgiro()
    {
        $o = new Organization();
        $o->setPlusgiro(new PlusGiro('111111-1'));
        $this->assertEquals(
            '111111-1',
            (string)$o->getPlusgiro()
        );
    }

    public function testName()
    {
        $o = new Organization();
        $o->setName('foobar');
        $this->assertEquals(
            'foobar',
            $o->getName()
        );
    }

    public function testAgCustomerNumber()
    {
        $o = new Organization();
        $o->setAgCustomerNumber('123456');
        $this->assertEquals(
            '123456',
            $o->getAgCustomerNumber()
        );
    }
}
