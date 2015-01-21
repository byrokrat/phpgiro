<?php

namespace ledgr\giro;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException ledgr\giro\Exception\ParserException
     */
    public function testEmtpyXmlException()
    {
        $strategy = $this->getMock(
            'ledgr\giro\StrategyInterface',
            array(),
            array(),
            '',
            false
        );
        $parser = new Parser($strategy);
        $parser->createDomDocument('');
    }

    /**
     * @expectedException ledgr\giro\Exception\ParserException
     */
    public function testNotWellFormedXMLException()
    {
        $strategy = $this->getMock(
            'ledgr\giro\StrategyInterface',
            array(),
            array(),
            '',
            false
        );
        $parser = new Parser($strategy);
        $parser->createDomDocument('foobar');
    }

    /**
     * @expectedException ledgr\giro\Exception\ParserException
     */
    public function testUnknownLineException()
    {
        $strategy = $this->getMock(
            'ledgr\giro\StrategyInterface',
            array(),
            array(),
            '',
            false
        );
        $strategy->expects($this->once())
            ->method('getRegexpMap')
            ->will($this->returnValue(array()));

        $parser = new Parser($strategy);
        $parser->parseLine('');
    }

    public function testParserLine()
    {
        $strategy = $this->getMock(
            'ledgr\giro\StrategyInterface',
            array(),
            array(),
            '',
            false
        );
        $strategy->expects($this->once())
            ->method('getRegexpMap')
            ->will($this->returnValue(array('/.*/' => 'clear')));

        // clear() should be called once since it is maped to parsing regexp
        $strategy->expects($this->once())
            ->method('clear');

        $parser = new Parser($strategy);
        $parser->parseLine('');
    }

    public function testParse()
    {
        $strategy = $this->getMock(
            'ledgr\giro\StrategyInterface',
            array(),
            array(),
            '',
            false
        );

        $strategy->expects($this->once())
            ->method('getRegexpMap')
            ->will($this->returnValue(array('/.?/' => 'clear')));

        $strategy->expects($this->once())
            ->method('getXML')
            ->will($this->returnValue('<foo></foo>'));

        $parser = new Parser($strategy);
        $parser->parse(array(''));
    }
}
