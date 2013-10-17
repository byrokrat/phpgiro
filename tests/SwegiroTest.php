<?php
namespace iio\swegiro;

class SwegiroTest extends \PHPUnit_Framework_TestCase
{
    public function testTrimLines()
    {
        $swegiro = $this->getMock(
            '\iio\swegiro\Swegiro',
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
     * @expectedException iio\swegiro\Exception\ValidatorException
     */
    public function testUnvalidXML()
    {
        $factory = $this->getMock('\iio\swegiro\Factory\FactoryInterface');
        $validator = $this->getMock('\iio\swegiro\Validator\ValidatorInterface');

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
        $factory = $this->getMock('\iio\swegiro\Factory\FactoryInterface');

        $factory->expects($this->once())
            ->method('createSniffer')
            ->will($this->returnValue($this->getMock('\iio\swegiro\Sniffer\SnifferInterface')));

        $parser = $this->getMock(
            '\iio\swegiro\Parser\Parser',
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

        $validator = $this->getMock('\iio\swegiro\Validator\ValidatorInterface');
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
