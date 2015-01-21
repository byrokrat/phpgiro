<?php

namespace ledgr\giro;

class GiroTest extends \PHPUnit_Framework_TestCase
{
    public function testTrimLines()
    {
        $giro = $this->getMock(
            '\ledgr\giro\Giro',
            array('convertToXML'),
            array(),
            '',
            false
        );

        $this->assertEquals(
            array('b' => 'foo', 'd' => 'bar'),
            $giro->trimLines(
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
     * @expectedException ledgr\giro\Exception\ValidatorException
     */
    public function testUnvalidXML()
    {
        $factory = $this->getMock('\ledgr\giro\FactoryInterface');
        $validator = $this->getMock('\ledgr\giro\ValidatorInterface');

        $validator->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(false));

        $factory->expects($this->once())
            ->method('createValidator')
            ->will($this->returnValue($validator));

        $giro = new giro($factory);
        $giro->validateXML(new \DOMDocument);
    }

    public function testConvertToXML()
    {
        $factory = $this->getMock('\ledgr\giro\FactoryInterface');

        $factory->expects($this->once())
            ->method('createSniffer')
            ->will($this->returnValue($this->getMock('\ledgr\giro\SnifferInterface')));

        $parser = $this->getMock(
            '\ledgr\giro\Parser',
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

        $validator = $this->getMock('\ledgr\giro\ValidatorInterface');
        $validator->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));

        $factory->expects($this->once())
            ->method('createValidator')
            ->will($this->returnValue($validator));

        $giro = new giro($factory);
        $domDocument = $giro->convertToXML(array());

        $this->assertInstanceOf(
            '\DOMDocument',
            $domDocument
        );
    }
}
