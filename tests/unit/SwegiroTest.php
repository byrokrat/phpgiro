<?php
namespace itbz\swegiro;

class SwegiroTest extends \PHPUnit_Framework_TestCase
{
    public function testTrimLines()
    {
        $swegiro = $this->getMock(
            '\itbz\swegiro\Swegiro',
            array('convertToXML'),
            array(),
            '',
            false
        );

        $this->assertEquals(
            array('b' => 'foo', 'd' => 'bar'),
            $swegiro->trimLines(
                array(
                    'a' => ' ',
                    'b' => 'foo',
                    'c' => "  \r\n",
                    'd' => 'bar',
                    'e' => "\t"
                )
            ),
            'Lines containing only white-space-characters should be removed.'
        );
    }

    /**
     * @expectedException itbz\swegiro\Exception\ValidatorException
     */
    public function testUnvalidXML()
    {
        $factory = $this->getMock('\itbz\swegiro\FactoryInterface');
        $validator = $this->getMock('\itbz\swegiro\ValidatorInterface');

        $validator->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(false));

        $factory->expects($this->once())
            ->method('createValidator')
            ->will($this->returnValue($validator));

        $swegiro = new Swegiro($factory);
        $swegiro->validateXML(new \DOMDocument);
    }

    public function testConvertToXML()
    {
        $factory = $this->getMock('\itbz\swegiro\FactoryInterface');

        $factory->expects($this->once())
            ->method('createSniffer')
            ->will($this->returnValue($this->getMock('\itbz\swegiro\SnifferInterface')));

        $parser = $this->getMock(
            '\itbz\swegiro\Parser\Parser',
            array(),
            array(),
            '',
            false
        );

        $parser->expects($this->once())
            ->method('parse')
            ->will($this->returnValue(new \DOMDocument));

        $factory->expects($this->once())
            ->method('createParser')
            ->will($this->returnValue($parser));

        $validator = $this->getMock('\itbz\swegiro\ValidatorInterface');
        $validator->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));

        $factory->expects($this->once())
            ->method('createValidator')
            ->will($this->returnValue($validator));

        $swegiro = new Swegiro($factory);
        $domDocument = $swegiro->convertToXML(array());

        $this->assertInstanceOf(
            '\DOMDocument',
            $domDocument
        );
    }
}
