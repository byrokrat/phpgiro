<?php
namespace itbz\phpautogiro\Parser;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException itbz\phpautogiro\Exception\ParserException
     */
    public function testEmtpyXmlException()
    {
        $strategy = $this->getMock('itbz\phpautogiro\Parser\StrategyInterface');
        $parser = new Parser($strategy);
        $parser->parse(array());
    }

    /**
     * @expectedException itbz\phpautogiro\Exception\ParserException
     */
    public function testInvalidXmlException()
    {
        $strategy = $this->getMock('itbz\phpautogiro\Parser\StrategyInterface');

        $strategy->expects($this->once())
            ->method('getXML')
            ->will($this->returnValue('foobar'));

        $parser = new Parser($strategy);
        $parser->parse(array());
    }

    public function testDtdException()
    {
        /*
            här ska jag testa hur det blir när DTD inte matchar dokument

            men jag har problemet med sökvägen till DTD kvar att lösa...
         */
    }

    public function testParse()
    {
        $strategy = $this->getMock('itbz\phpautogiro\Parser\StrategyInterface');

        $strategy->expects($this->once())
            ->method('clear');

        $strategy->expects($this->atLeastOnce())
            ->method('parseLine');

        $xml = '<?xml version="1.0"?>';
        $xml .= "\n<!DOCTYPE foo [<!ELEMENT foo (#PCDATA)>]>";
        $xml .= "\n<foo></foo>";

        $strategy->expects($this->once())
            ->method('getXML')
            ->will($this->returnValue($xml));

        $parser = new Parser($strategy);
        $parser->parse(array(''));
    }
}
