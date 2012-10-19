<?php
namespace itbz\swegiro\Parser;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException itbz\swegiro\Exception\ParserException
     */
    public function testEmtpyXmlException()
    {
        $strategy = $this->getMock('itbz\swegiro\Parser\StrategyInterface');
        $parser = new Parser($strategy);
        $parser->parse(array());
    }

    /**
     * @expectedException itbz\swegiro\Exception\ParserException
     */
    public function testNotWellFormedXMLException()
    {
        $strategy = $this->getMock('itbz\swegiro\Parser\StrategyInterface');
        $strategy->expects($this->once())
            ->method('getXML')
            ->will($this->returnValue('foobar'));

        $parser = new Parser($strategy);
        $parser->parse(array());
    }

    public function testParse()
    {
        $strategy = $this->getMock('itbz\swegiro\Parser\StrategyInterface');

        $strategy->expects($this->once())
            ->method('clear');

        $strategy->expects($this->atLeastOnce())
            ->method('parseLine');

        $strategy->expects($this->once())
            ->method('getXML')
            ->will($this->returnValue('<foo></foo>'));

        $parser = new Parser($strategy);
        $parser->parse(array(''));
    }
}
