<?php
namespace itbz\swegiro\Parser;

use itbz\swegiro\Parser\Strategy\AG\LayoutH as Strategy;

class AbstractStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function testClear()
    {
        $writer = $this->getMock(
            'itbz\swegiro\XMLWriter',
            array()
        );

        $writer->expects($this->once())
            ->method('clear');

        $h = new Strategy($writer);
        $h->clear();
    }

    public function testGetXML()
    {
        $writer = $this->getMock(
            'itbz\swegiro\XMLWriter',
            array()
        );

        $writer->expects($this->once())
            ->method('getXML');

        $h = new Strategy($writer);
        $h->getXML();
    }
}
