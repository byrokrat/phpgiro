<?php
namespace iio\autogiro\Builder;

use iio\stb\Banking\Bankgiro;

class OrganizationTest extends \PHPUnit_Framework_TestCase
{
    public function testDataWrapper()
    {
        $o = new Organization(
            'foobar',
            '123456',
            new Bankgiro('111-1111')
        );

        $this->assertEquals(
            'foobar',
            $o->getName()
        );

        $this->assertEquals(
            '123456',
            $o->getAgCustomerNumber()
        );

        $this->assertEquals(
            '111-1111',
            (string)$o->getBankgiro()
        );
    }
}
