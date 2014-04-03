<?php
namespace ledgr\autogiro;

class AutogiroFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateSniffer()
    {
        $factory = new AutogiroFactory;
        $this->assertInstanceOf(
            '\ledgr\autogiro\Sniffer',
            $factory->createSniffer()
        );
    }

    public function testCreateValidator()
    {
        $factory = new AutogiroFactory;
        $this->assertInstanceOf(
            '\ledgr\giro\DtdValidator',
            $factory->createValidator()
        );
    }

    public function testCreateParser()
    {
        $factory = new AutogiroFactory;
        $this->assertInstanceOf(
            '\ledgr\giro\Parser',
            $factory->createParser(AutogiroFactory::LAYOUT_AG_H)
        );
    }
}
