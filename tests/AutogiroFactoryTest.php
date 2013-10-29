<?php
namespace iio\autogiro;

class AutogiroFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateSniffer()
    {
        $factory = new AutogiroFactory;
        $this->assertInstanceOf(
            '\iio\autogiro\Sniffer',
            $factory->createSniffer()
        );
    }

    public function testCreateValidator()
    {
        $factory = new AutogiroFactory;
        $this->assertInstanceOf(
            '\iio\giro\DtdValidator',
            $factory->createValidator()
        );
    }

    public function testCreateParser()
    {
        $factory = new AutogiroFactory;
        $this->assertInstanceOf(
            'iio\giro\Parser',
            $factory->createParser(AutogiroFactory::LAYOUT_AG_H)
        );
    }
}
