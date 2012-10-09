<?php
namespace itbz\phpautogiro;

class SnifferTest extends \PHPUnit_Framework_TestCase
{
    public function testAgpE()
    {
        $sniffer = new Sniffer();
        $lines = file(__DIR__ . '/samples/agp/AGPLayoutE.txt');
        $this->assertEquals(
            Sniffer::LAYOUT_AGP_E,
            $sniffer->sniff($lines)
        );
    }

    public function testAgpF()
    {
        $sniffer = new Sniffer();
        $lines = file(__DIR__ . '/samples/agp/AGPLayoutF.txt');
        $this->assertEquals(
            Sniffer::LAYOUT_AGP_F,
            $sniffer->sniff($lines)
        );
    }

    public function testAgpG()
    {
        $sniffer = new Sniffer();
        $lines = file(__DIR__ . '/samples/agp/AGPLayoutG.txt');
        $this->assertEquals(
            Sniffer::LAYOUT_AGP_G,
            $sniffer->sniff($lines)
        );
    }

    public function testAgpH()
    {
        $sniffer = new Sniffer();
        $lines = file(__DIR__ . '/samples/agp/AGPLayoutH.txt');
        $this->assertEquals(
            Sniffer::LAYOUT_AGP_H,
            $sniffer->sniff($lines)
        );
    }
}
