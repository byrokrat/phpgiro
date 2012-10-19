<?php
namespace itbz\swegiro\Factory;

class AgFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateSniffer()
    {
        $factory = new AgFactory;
        $this->assertInstanceOf(
            '\itbz\swegiro\Sniffer\AgSniffer',
            $factory->createSniffer()
        );
    }

    public function testCreateValidator()
    {
        $factory = new AgFactory;
        $this->assertInstanceOf(
            '\itbz\swegiro\Validator\DtdValidator',
            $factory->createValidator()
        );
    }

    /**
     * @expectedException itbz\swegiro\Exception\StrategyException
     */
    public function testInvalidFlag()
    {
        $factory = new AgFactory;
        $factory->createParserStrategy('foobar');
    }

    public function testCreateParserStrategy()
    {
        $factory = new AgFactory;
        $this->assertInstanceOf(
            'itbz\swegiro\Parser\Strategy\AG\LayoutH',
            $factory->createParserStrategy(AgFactory::LAYOUT_AG_H)
        );
    }
}
