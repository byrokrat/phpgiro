<?php
namespace itbz\swegiro\Parser;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException itbz\swegiro\Exception\ParserException
     */
    public function testEmtpyXmlException()
    {
        $strategy = $this->getMock(
            'itbz\swegiro\Parser\AbstractStrategy',
            array(),
            array(),
            '',
            false
        );
        $parser = new Parser($strategy);
        $parser->createDomDocument('');
    }

    /**
     * @expectedException itbz\swegiro\Exception\ParserException
     */
    public function testNotWellFormedXMLException()
    {
        $strategy = $this->getMock(
            'itbz\swegiro\Parser\AbstractStrategy',
            array(),
            array(),
            '',
            false
        );
        $parser = new Parser($strategy);
        $parser->createDomDocument('foobar');
    }

    /**
     * @expectedException itbz\swegiro\Exception\ParserException
     */
    public function testUnknownLineException()
    {
        $strategy = $this->getMock(
            'itbz\swegiro\Parser\AbstractStrategy',
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
            'itbz\swegiro\Parser\AbstractStrategy',
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
            'itbz\swegiro\Parser\AbstractStrategy',
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
