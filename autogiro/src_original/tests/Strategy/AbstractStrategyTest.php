<?php
namespace ledgr\autogiro\Strategy;

use ledgr\autogiro\Strategy\LayoutH as Strategy;

class AbstractStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function testClear()
    {
        $writer = $this->getMock(
            'ledgr\giro\XMLWriter',
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
            'ledgr\giro\XMLWriter',
            array()
        );

        $writer->expects($this->once())
            ->method('getXML');

        $h = new Strategy($writer);
        $h->getXML();
    }
}
