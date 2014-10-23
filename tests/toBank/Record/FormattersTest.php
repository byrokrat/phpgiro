<?php

namespace ledgr\autogiro\toBank\Record;

use Mockery as m;

class FormattersTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSet()
    {
        $formatter = m::mock('ledgr\autogiro\toBank\Record\Formatter');
        $formatters = new Formatters;

        $this->assertNotSame(
            $formatter,
            $formatters->getPayerNumberFormatter()
        );
        $formatters->setPayerNumberFormatter($formatter);
        $this->assertSame(
            $formatter,
            $formatters->getPayerNumberFormatter()
        );

        $this->assertNotSame(
            $formatter,
            $formatters->getIdFormatter()
        );
        $formatters->setIdFormatter($formatter);
        $this->assertSame(
            $formatter,
            $formatters->getIdFormatter()
        );

        $this->assertNotSame(
            $formatter,
            $formatters->getBankgiroFormatter()
        );
        $formatters->setBankgiroFormatter($formatter);
        $this->assertSame(
            $formatter,
            $formatters->getBankgiroFormatter()
        );
    }
}
