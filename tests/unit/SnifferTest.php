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

    public function testAgpD()
    {
        $sniffer = new Sniffer();
        $lines = file(__DIR__ . '/samples/agp/AGPLayoutD.txt');
        $this->assertEquals(
            Sniffer::LAYOUT_AGP_D,
            $sniffer->sniff($lines),
            'Layout D should be identified from AGPLayoutD.txt'
        );
    }

    public function testEmptyLineEndOfFile()
    {
        $sniffer = new Sniffer();
        $lines = file(__DIR__ . '/samples/agp/AGPLayoutD.txt');
        $lines[] = "   \r\n";
        $this->assertEquals(
            Sniffer::LAYOUT_AGP_D,
            $sniffer->sniff($lines),
            'Layout D should be identified ignoring empty line at the end'
        );
    }

    public function testAgpABC()
    {
        $sniffer = new Sniffer();
        $lines = file(__DIR__ . '/samples/agp/AGPLayoutA.txt');
        $this->assertEquals(
            Sniffer::LAYOUT_AGP_ABC,
            $sniffer->sniff($lines),
            'Layout ABC should be identified from AGPLayoutA.txt'
        );

        $lines = file(__DIR__ . '/samples/agp/AGPLayoutB.txt');
        $this->assertEquals(
            Sniffer::LAYOUT_AGP_ABC,
            $sniffer->sniff($lines),
            'Layout ABC should be identified from AGPLayoutB.txt'
        );

        $lines = file(__DIR__ . '/samples/agp/AGPLayoutC.txt');
        $this->assertEquals(
            Sniffer::LAYOUT_AGP_ABC,
            $sniffer->sniff($lines),
            'Layout ABC should be identified from AGPLayoutC.txt'
        );
    }

    public function testNewF()
    {
        $sniffer = new Sniffer;
        $lines = file(__DIR__ . '/samples/new/avvisade-betalningsuppdrag.txt');
        $this->assertEquals(
            Sniffer::LAYOUT_NEW_F,
            $sniffer->sniff($lines),
            'Layout NEW F should be identified from avvisade-betalningsuppdrag.txt'
        );
    }

    public function testNewD()
    {
        $sniffer = new Sniffer;
        $lines = file(__DIR__ . '/samples/new/betalningsspecifikation.txt');
        $this->assertEquals(
            Sniffer::LAYOUT_NEW_D,
            $sniffer->sniff($lines),
            'Layout NEW D should be identified from betalningsspecifikation.txt'
        );
    }

    public function testNewG()
    {
        $sniffer = new Sniffer;
        $lines = file(__DIR__ . '/samples/new/makuleringar-andringar.txt');
        $this->assertEquals(
            Sniffer::LAYOUT_NEW_G,
            $sniffer->sniff($lines),
            'Layout NEW G should be identified from makuleringar-andringar.txt'
        );
    }

    public function testNewE()
    {
        $sniffer = new Sniffer;
        $lines = file(__DIR__ . '/samples/new/medgivandeavisering.txt');
        $this->assertEquals(
            Sniffer::LAYOUT_NEW_E,
            $sniffer->sniff($lines),
            'Layout NEW E should be identified from medgivandeavisering.txt'
        );
    }

    /*public function testNewH()
    {
        $sniffer = new Sniffer;
        $lines = file(__DIR__ . '/samples/new/nya-medgivanden-internetbank.txt');
        $this->assertEquals(
            Sniffer::LAYOUT_NEW_H,
            $sniffer->sniff($lines),
            'Layout NEW H should be identified from medgivandeavisering.txt'
        );
    }*/

    public function testNewI()
    {
        $sniffer = new Sniffer;
        $lines = file(__DIR__ . '/samples/new/utdrag-bevakningsregistret.txt');
        $this->assertEquals(
            Sniffer::LAYOUT_NEW_I,
            $sniffer->sniff($lines),
            'Layout NEW I should be identified from utdrag-bevakningsregistret.txt'
        );
    }

    public function testNewJ()
    {
        $sniffer = new Sniffer;
        $lines = file(__DIR__ . '/samples/new/utdrag-medgivanderegistret.txt');
        $this->assertEquals(
            Sniffer::LAYOUT_NEW_J,
            $sniffer->sniff($lines),
            'Layout NEW J should be identified from utdrag-medgivanderegistret.txt'
        );
    }

    public function testBgMax()
    {
        $sniffer = new Sniffer;
        $lines = file(__DIR__ . '/samples/new/autogiro_bg_max_formmat.txt');
        $this->assertEquals(
            Sniffer::LAYOUT_BGMAX,
            $sniffer->sniff($lines),
            'Layout BGMAX should be identified from autogiro_bg_max_formmat.txt'
        );
    }

    /**
     * @expectedException itbz\phpautogiro\Exception\SniffException
     */
    public function testNoMatch()
    {
        $sniffer = new Sniffer;
        $sniffer->sniff(array('sdfasdf', 'adsfasdf'));
    }
}
