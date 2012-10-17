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
        $validator = $this->getMock('itbz\swegiro\ValidatorInterface');
        $parser = new Parser($strategy, $validator);
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

        $validator = $this->getMock('itbz\swegiro\ValidatorInterface');

        $parser = new Parser($strategy, $validator);
        $parser->parse(array());
    }

    /**
     * @expectedException itbz\swegiro\Exception\ValidatorException
     */
    public function testValidatorException()
    {
        $strategy = $this->getMock('itbz\swegiro\Parser\StrategyInterface');
        $strategy->expects($this->once())
            ->method('getXML')
            ->will($this->returnValue('<foo></foo>'));

        $validator = $this->getMock('itbz\swegiro\ValidatorInterface');
        $validator->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(false));

        $parser = new Parser($strategy, $validator);
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

        $validator = $this->getMock('itbz\swegiro\ValidatorInterface');
        $validator->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));

        $parser = new Parser($strategy, $validator);
        $parser->parse(array(''));
    }
}
