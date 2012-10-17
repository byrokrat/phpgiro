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
        $validator = $this->getMock('itbz\phpautogiro\ValidatorInterface');
        $parser = new Parser($strategy, $validator);
        $parser->parse(array());
    }

    /**
     * @expectedException itbz\phpautogiro\Exception\ParserException
     */
    public function testNotWellFormedXMLException()
    {
        $strategy = $this->getMock('itbz\phpautogiro\Parser\StrategyInterface');
        $strategy->expects($this->once())
            ->method('getXML')
            ->will($this->returnValue('foobar'));

        $validator = $this->getMock('itbz\phpautogiro\ValidatorInterface');

        $parser = new Parser($strategy, $validator);
        $parser->parse(array());
    }

    /**
     * @expectedException itbz\phpautogiro\Exception\ValidatorException
     */
    public function testValidatorException()
    {
        $strategy = $this->getMock('itbz\phpautogiro\Parser\StrategyInterface');
        $strategy->expects($this->once())
            ->method('getXML')
            ->will($this->returnValue('<foo></foo>'));

        $validator = $this->getMock('itbz\phpautogiro\ValidatorInterface');
        $validator->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(false));

        $parser = new Parser($strategy, $validator);
        $parser->parse(array());
    }

    public function testParse()
    {
        $strategy = $this->getMock('itbz\phpautogiro\Parser\StrategyInterface');
        $strategy->expects($this->once())
            ->method('clear');
        $strategy->expects($this->atLeastOnce())
            ->method('parseLine');
        $strategy->expects($this->once())
            ->method('getXML')
            ->will($this->returnValue('<foo></foo>'));

        $validator = $this->getMock('itbz\phpautogiro\ValidatorInterface');
        $validator->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));

        $parser = new Parser($strategy, $validator);
        $parser->parse(array(''));
    }
}
