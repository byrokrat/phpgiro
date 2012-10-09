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
            $sniffer->sniff($lines),
            'Layout E should be identified from AGPLayoutE.txt'
        );
    }

    public function testAgpF()
    {
        $sniffer = new Sniffer();
        $lines = file(__DIR__ . '/samples/agp/AGPLayoutF.txt');
        $this->assertEquals(
            Sniffer::LAYOUT_AGP_F,
            $sniffer->sniff($lines),
            'Layout F should be identified from AGPLayoutF.txt'
        );
    }

    public function testAgpG()
    {
        $sniffer = new Sniffer();
        $lines = file(__DIR__ . '/samples/agp/AGPLayoutG.txt');
        $this->assertEquals(
            Sniffer::LAYOUT_AGP_G,
            $sniffer->sniff($lines),
            'Layout G should be identified from AGPLayoutG.txt'
        );
    }

    public function testAgpH()
    {
        $sniffer = new Sniffer();
        $lines = file(__DIR__ . '/samples/agp/AGPLayoutH.txt');
        $this->assertEquals(
            Sniffer::LAYOUT_AGP_H,
            $sniffer->sniff($lines),
            'Layout H should be identified from AGPLayoutH.txt'
        );
    }

    public function testEmptyLineStartOfFile()
    {
        $sniffer = new Sniffer();
        $lines = file(__DIR__ . '/samples/agp/AGPLayoutH.txt');
        array_unshift($lines, "    \r\n");
        $this->assertEquals(
            Sniffer::LAYOUT_AGP_H,
            $sniffer->sniff($lines),
            'Layout H should be identified ignoring empty line at the beginning'
        );
    }
}
