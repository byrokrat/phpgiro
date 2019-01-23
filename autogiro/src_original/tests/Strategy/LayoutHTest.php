<?php
namespace ledgr\autogiro\Strategy;

use ledgr\banking\Bankgiro;
use DateTime;

class LayoutHTest extends \PHPUnit_Framework_TestCase
{
    public function testParseHead()
    {
        $writer = $this->getMock('ledgr\giro\XMLWriter');
        $h = new LayoutH($writer);
        $h->parseHead(array('20121114', '111-1111'));

        $this->assertEquals(
            new DateTime('20121114'),
            $h->getFileDate()
        );

        $this->assertEquals(
            new Bankgiro('111-1111'),
            $h->getBgNr()
        );
    }

    /**
     * @expectedException ledgr\giro\Exception\StrategyException
     */
    public function testParseConsentBGException()
    {
        $writer = $this->getMock('ledgr\giro\XMLWriter');
        $h = new LayoutH($writer);
        $h->setBgNr(new Bankgiro('111-1111'));
        $h->parseConsent(
            array(
                new Bankgiro('222-2222'),
                null,
                null,
                null,
                null,
                null
            )
        );
    }

    public function testParseInfo()
    {
        $writer = $this->getMock('ledgr\giro\XMLWriter');

        $writer->expects($this->once())
            ->method('writeElement')
            ->with('info', 'text');

        $h = new LayoutH($writer);
        $h->parseInfo(array('text'));
    }

    public function testParseAddress()
    {
        $writer = $this->getMock('ledgr\giro\XMLWriter');

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
     * @expectedException ledgr\giro\Exception\StrategyException
     */
    public function testParseFootNrPostsException()
    {
        $writer = $this->getMock('ledgr\giro\XMLWriter');
        $h = new LayoutH($writer);
        $h->parseFoot(array('20121114', '1'));
    }

    /**
     * @expectedException ledgr\giro\Exception\StrategyException
     */
    public function testParseFootDateException()
    {
        $writer = $this->getMock('ledgr\giro\XMLWriter');
        $h = new LayoutH($writer);
        $h->setFileDate(new DateTime('20120101'));
        $h->parseFoot(array('20121114', '0'));
    }

    public function testParseFoot()
    {
        $writer = $this->getMock('ledgr\giro\XMLWriter');
        $h = new LayoutH($writer);
        $h->setFileDate(new DateTime('20121114'));
        $h->parseFoot(array('20121114', '0'));
    }
}
