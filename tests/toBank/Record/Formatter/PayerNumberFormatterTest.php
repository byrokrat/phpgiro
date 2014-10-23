<?php

namespace ledgr\autogiro\toBank\Record\Formatter;

use Mockery as m;

class PayerNumberFormatterTest extends \PHPUnit_Framework_TestCase
{
    public function testFormat()
    {
        $creditor = m::mock('ledgr\billing\LegalPerson', function ($mock) {
            $mock->shouldReceive('getId->getDate->format')->once()->andReturn('YYMMDD');
            $mock->shouldReceive('getId->getIndividualNr')->once()->andReturn('NNN');
            $mock->shouldReceive('getId->getCheckDigit')->once()->andReturn('K');
        });

        $this->assertSame(
            '000000YYMMDDNNNK',
            (new PayerNumberFormatter)->format($creditor)
        );
    }
}
