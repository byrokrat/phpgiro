<?php
namespace itbz\swegiro;

class AgXmlConverterTest extends \PHPUnit_Framework_TestCase
{
    public function testConvert()
    {
        $sniffer = $this->getMock('itbz\swegiro\Sniffer');

        $sniffer->expects($this->once())
            ->method('sniff')
            ->will($this->returnValue(LayoutInterface::LAYOUT_AG_H));

        $parserFactory = $this->getMock('itbz\swegiro\ParserFactory');

        $parser = $this->getMock(
            'itbz\swegiro\Parser\Parser',
            array(),
            array(),
            '',
            false
        );

        // Parser factory should build a parser using the sniffed layout id
        $parserFactory->expects($this->once())
            ->method('build')
            ->with(LayoutInterface::LAYOUT_AG_H)
            ->will($this->returnValue($parser));

        // Parser should be called
        $parser->expects($this->once())
            ->method('parse');

        $converter = new AgXmlConverter($sniffer, $parserFactory);
        $converter->convert(array());
    }

    public function testRemoveBlankLines()
    {
        $rawData = array(
            'a' => "",
            'b' => "  \r\n",
            'c' => "foo",
            'd' => " ",
            'e' => "bar",
            'f' => "\t\n "
        );

        $trimedData = array(
            'c' => "foo",
            'e' => "bar",
        );

        $sniffer = $this->getMock('itbz\swegiro\Sniffer');
        $parserFactory = $this->getMock('itbz\swegiro\ParserFactory');

        $parser = $this->getMock(
            'itbz\swegiro\Parser\Parser',
            array(),
            array(),
            '',
            false
        );

        $parserFactory->expects($this->once())
            ->method('build')
            ->will($this->returnValue($parser));

        // When parser receives the data it should be trimmed
        $parser->expects($this->once())
            ->method('parse')
            ->with($trimedData);

        $converter = new AgXmlConverter($sniffer, $parserFactory);
        $converter->convert($rawData);
    }
}
