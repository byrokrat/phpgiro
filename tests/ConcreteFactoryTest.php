<?php
namespace iio\autogiro;

class ConcreteFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateSniffer()
    {
        $factory = new ConcreteFactory;
        $this->assertInstanceOf(
            '\iio\autogiro\Sniffer',
            $factory->createSniffer()
        );
    }

    public function testCreateValidator()
    {
        $factory = new ConcreteFactory;
        $this->assertInstanceOf(
            '\iio\giro\DtdValidator',
            $factory->createValidator()
        );
    }

    public function testCreateParser()
    {
        $factory = new ConcreteFactory;
        $this->assertInstanceOf(
            'iio\giro\Parser',
            $factory->createParser(ConcreteFactory::LAYOUT_AG_H)
        );
    }
}
