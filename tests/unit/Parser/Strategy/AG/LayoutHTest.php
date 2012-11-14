<?php
namespace itbz\swegiro\Parser\Strategy\AG;

class LayoutHTest extends \PHPUnit_Framework_TestCase
{
    public function testParseInfo()
    {
        $writer = $this->getMock('itbz\swegiro\XMLWriter');

        $writer->expects($this->once())
            ->method('writeElement')
            ->with('info', 'text');

        $h = new LayoutH($writer);
        $h->parseInfo(array('text'));
    }

    public function testParseAddress()
    {
        $writer = $this->getMock('itbz\swegiro\XMLWriter');

        $writer->expects($this->once())
            ->method('currentElement')
            ->will($this->returnValue(''));

        $writer->expects($this->once())
            ->method('startElement')
            ->with('address');

        $writer->expects($this->atLeastOnce())
            ->method('writeElement')
            ->with('line');

        $h = new LayoutH($writer);
        $h->parseAddress(
            array(
                'Somestreet 14',
                '12345 town'
            )
        );
    }

    /**
     * @expectedException itbz\swegiro\Exception\ContentException
     */
    public function testParseFootNrPostsException()
    {
        $writer = $this->getMock('itbz\swegiro\XMLWriter');
        $h = new LayoutH($writer);
        $h->parseFoot(array('20121114', '1'));
    }
}
