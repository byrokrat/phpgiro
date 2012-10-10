<?php
namespace itbz\phpautogiro;

class SnifferTest extends \PHPUnit_Framework_TestCase
{
    public function testLayoutABC()
    {
        $sniffer = new Sniffer();

        $lines = file(__DIR__ . '/samples/agp/AGPLayoutA.txt');
        $this->assertEquals(
            Sniffer::LAYOUT_ABC,
            $sniffer->sniff($lines),
            'Layout ABC should be identified from AGPLayoutA.txt'
        );

        $lines = file(__DIR__ . '/samples/agp/AGPLayoutB.txt');
        $this->assertEquals(
            Sniffer::LAYOUT_ABC,
            $sniffer->sniff($lines),
            'Layout ABC should be identified from AGPLayoutB.txt'
        );

        $lines = file(__DIR__ . '/samples/agp/AGPLayoutC.txt');
        $this->assertEquals(
            Sniffer::LAYOUT_ABC,
            $sniffer->sniff($lines),
            'Layout ABC should be identified from AGPLayoutC.txt'
        );

        $lines = file(__DIR__ . '/samples/new/andringsunderlag.txt');
        $this->assertEquals(
            Sniffer::LAYOUT_ABC,
            $sniffer->sniff($lines),
            'Layout ABC should be identified from andringsunderlag.txt'
        );

        $lines = file(__DIR__ . '/samples/new/betalningsunderlag.txt');
        $this->assertEquals(
            Sniffer::LAYOUT_ABC,
            $sniffer->sniff($lines),
            'Layout ABC should be identified from betalningsunderlag.txt'
        );

        $lines = file(__DIR__ . '/samples/new/medgivandeunderlag.txt');
        $this->assertEquals(
            Sniffer::LAYOUT_ABC,
            $sniffer->sniff($lines),
            'Layout ABC should be identified from medgivandeunderlag.txt'
        );
    }

    public function testLayoutD()
    {
        $sniffer = new Sniffer;

        $lines = file(__DIR__ . '/samples/new/betalningsspecifikation.txt');
        $this->assertEquals(
            Sniffer::LAYOUT_D,
            $sniffer->sniff($lines),
            'Layout D should be identified from betalningsspecifikation.txt'
        );

        $lines = file(__DIR__ . '/samples/agp/AGPLayoutD.txt');
        $this->assertEquals(
            Sniffer::LAYOUT_PRIV_D,
            $sniffer->sniff($lines),
            'Layout PRIV D should be identified from AGPLayoutD.txt'
        );
    }

    public function testLayoutE()
    {
        $sniffer = new Sniffer;

        $lines = file(__DIR__ . '/samples/new/medgivandeavisering.txt');
        $this->assertEquals(
            Sniffer::LAYOUT_E,
            $sniffer->sniff($lines),
            'Layout E should be identified from medgivandeavisering.txt'
        );

        $lines = file(__DIR__ . '/samples/agp/AGPLayoutE.txt');
        $this->assertEquals(
            Sniffer::LAYOUT_PRIV_E,
            $sniffer->sniff($lines),
            'Layout E should be identified from AGPLayoutE.txt'
        );
    }

    public function testLayoutF()
    {
        $sniffer = new Sniffer;

        $lines = file(__DIR__ . '/samples/new/avvisade-betalningsuppdrag.txt');
        $this->assertEquals(
            Sniffer::LAYOUT_F,
            $sniffer->sniff($lines),
            'Layout F should be identified from avvisade-betalningsuppdrag.txt'
        );

        $lines = file(__DIR__ . '/samples/agp/AGPLayoutF.txt');
        $this->assertEquals(
            Sniffer::LAYOUT_PRIV_F,
            $sniffer->sniff($lines),
            'Layout PRIV F should be identified from AGPLayoutF.txt'
        );
    }

    public function testLayoutG()
    {
        $sniffer = new Sniffer;

        $lines = file(__DIR__ . '/samples/new/makuleringar-andringar.txt');
        $this->assertEquals(
            Sniffer::LAYOUT_G,
            $sniffer->sniff($lines),
            'Layout G should be identified from makuleringar-andringar.txt'
        );

        $lines = file(__DIR__ . '/samples/agp/AGPLayoutG.txt');
        $this->assertEquals(
            Sniffer::LAYOUT_PRIV_G,
            $sniffer->sniff($lines),
            'Layout PRIV G should be identified from AGPLayoutG.txt'
        );
    }

    public function testLayoutH()
    {
        $sniffer = new Sniffer;

        $lines = file(__DIR__ . '/samples/new/nya-medgivanden-internetbank.txt');
        $this->assertEquals(
            Sniffer::LAYOUT_H,
            $sniffer->sniff($lines),
            'Layout H should be identified from medgivandeavisering.txt'
        );

        $sniffer = new Sniffer();
        $lines = file(__DIR__ . '/samples/agp/AGPLayoutH.txt');
        $this->assertEquals(
            Sniffer::LAYOUT_H,
            $sniffer->sniff($lines),
            'Layout H should be identified from AGPLayoutH.txt'
        );
    }

    public function testLayoutIJ()
    {
        $sniffer = new Sniffer;
        $lines = file(__DIR__ . '/samples/new/utdrag-bevakningsregistret.txt');
        $this->assertEquals(
            Sniffer::LAYOUT_I,
            $sniffer->sniff($lines),
            'Layout I should be identified from utdrag-bevakningsregistret.txt'
        );

        $lines = file(__DIR__ . '/samples/new/utdrag-medgivanderegistret.txt');
        $this->assertEquals(
            Sniffer::LAYOUT_J,
            $sniffer->sniff($lines),
            'Layout J should be identified from utdrag-medgivanderegistret.txt'
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


    public function testBlankLines()
    {
        $sniffer = new Sniffer();

        $lines = file(__DIR__ . '/samples/agp/AGPLayoutD.txt');
        $lines[] = "   \r\n";
        $this->assertEquals(
            Sniffer::LAYOUT_PRIV_D,
            $sniffer->sniff($lines),
            'Layout PRIV D should be identified ignoring empty line at the end'
        );

        $lines = file(__DIR__ . '/samples/agp/AGPLayoutH.txt');
        array_unshift($lines, "    \r\n");
        $this->assertEquals(
            Sniffer::LAYOUT_H,
            $sniffer->sniff($lines),
            'Layout H should be identified ignoring empty line at the beginning'
        );
    }
}
