<?php

namespace ledgr\autogiro\toBank;

use Mockery as m;

class MandateFileTest extends \PHPUnit_Framework_TestCase
{
    public function testRemove()
    {
        $mandateFile = m::mock('ledgr\autogiro\toBank\MandateFile')->makePartial();
        $mandateFile->shouldReceive('addLine')->once()->with('03000000000A000000YYMMDDNNNKCCCC00000000000N                                    ');
        $mandateFile->shouldReceive('getCreditor->getAccount')->once()->andReturn('A');

        $debtor = m::mock('ledgr\billing\LegalPerson');
        $debtor->shouldReceive('getId->getDate->format')->once()->andReturn('YYMMDD');
        $debtor->shouldReceive('getId->getIndividualNr')->once()->andReturn('NNN');
        $debtor->shouldReceive('getId->getCheckDigit')->once()->andReturn('K');
        $debtor->shouldReceive('getAccount->getClearing')->once()->andReturn('CCCC');
        $debtor->shouldReceive('getAccount->getNumber')->once()->andReturn('N');

        $mandateFile->remove($debtor);
    }
}
