<?php

namespace byrokrat\autogiro\toBank\Record\Formatter;

use Mockery as m;

class IdFormatterTest extends \PHPUnit_Framework_TestCase
{
    public function testFormat()
    {
        $creditor = m::mock('byrokrat\billing\LegalPerson', function ($mock) {
            $mock->shouldReceive('getId->getDate->format')->once()->andReturn('YYYYMMDD');
            $mock->shouldReceive('getId->getIndividualNr')->once()->andReturn('NNN');
            $mock->shouldReceive('getId->getCheckDigit')->once()->andReturn('K');
        });

        $this->assertSame(
            'YYYYMMDDNNNK',
            (new IdFormatter)->format($creditor)
        );
    }
}
