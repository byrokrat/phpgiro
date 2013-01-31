<?php
namespace iio\swegiro\Factory;

class AgFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateSniffer()
    {
        $factory = new AgFactory;
        $this->assertInstanceOf(
            '\iio\swegiro\Sniffer\AgSniffer',
            $factory->createSniffer()
        );
    }

    public function testCreateValidator()
    {
        $factory = new AgFactory;
        $this->assertInstanceOf(
            '\iio\swegiro\Validator\DtdValidator',
            $factory->createValidator()
        );
    }

    /**
     * @expectedException iio\swegiro\Exception\StrategyException
     */
    public function testInvalidFlag()
    {
        $factory = new AgFactory;
        $factory->createParser('foobar');
    }

    public function testCreateParser()
    {
        $factory = new AgFactory;
        $this->assertInstanceOf(
            'iio\swegiro\Parser\Parser',
            $factory->createParser(AgFactory::LAYOUT_AG_H)
        );
    }
}
