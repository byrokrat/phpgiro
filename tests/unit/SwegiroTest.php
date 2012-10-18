<?php
namespace itbz\swegiro;

class SwegiroTest extends \PHPUnit_Framework_TestCase
{
    public function testConvertToXML()
    {
        $factory = $this->getMock('\itbz\swegiro\FactoryInterface');

        $factory->expects($this->once())
            ->method('createSniffer')
            ->will($this->returnValue($this->getMock('\itbz\swegiro\SnifferInterface')));

        $strategy = $this->getMock('\itbz\swegiro\Parser\StrategyInterface');
        $strategy->expects($this->once())
            ->method('getXML')
            ->will($this->returnValue('<?xml version="1.0"?><foo></foo>'));

        $factory->expects($this->once())
            ->method('createParserStrategy')
            ->will($this->returnValue($strategy));

        $validator = $this->getMock('\itbz\swegiro\ValidatorInterface');
        $validator->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));

        $factory->expects($this->once())
            ->method('createValidator')
            ->will($this->returnValue($validator));

        $swegiro = new Swegiro($factory);
        $swegiro->convertToXML(array(''));
    }
}
