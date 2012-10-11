<?php
namespace itbz\phpautogiro;

class ParserFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException itbz\phpautogiro\Exception\StrategyException
     */
    public function testInvalidFlag()
    {
        $factory = new ParserFactory;
        $factory->build('foobar');
    }

    public function testBuild()
    {
        $factory = new ParserFactory;
        $parser = $factory->build(ParserFactory::LAYOUT_H);

        $this->assertInstanceOf(
            'itbz\phpautogiro\Parser\Parser',
            $parser
        );

        $this->assertInstanceOf(
            'itbz\phpautogiro\Parser\Strategy\LayoutH',
            $parser->getStrategy()
        );
    }
}
