<?php
namespace itbz\swegiro;

class ParserFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException itbz\swegiro\Exception\StrategyException
     */
    public function testInvalidFlag()
    {
        $factory = new ParserFactory;
        $factory->build('foobar');
    }

    public function testBuild()
    {
        $factory = new ParserFactory;
        $parser = $factory->build(ParserFactory::LAYOUT_AG_H);

        $this->assertInstanceOf(
            'itbz\swegiro\Parser\Parser',
            $parser
        );

        $this->assertInstanceOf(
            'itbz\swegiro\Parser\Strategy\AG\LayoutH',
            $parser->getStrategy()
        );
    }
}
