<?php
namespace itbz\phpautogiro;

class SnifferTest extends \PHPUnit_Framework_TestCase
{
    public function testSniff()
    {
        $sniffer = new Sniffer();
        $this->assertEquals(
            Sniffer::LAYOUT_D,
            $sniffer->sniff(array())
        );
    }
}
