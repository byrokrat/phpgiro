<?php

namespace byrokrat\autogiro\toBank\Record\Formatter;

use Mockery as m;

class BankgiroFormatterTest extends \PHPUnit_Framework_TestCase
{
    public function testFormatNoBankgiroException()
    {
        $account = m::mock('byrokrat\banking\BankAccountInterface', function ($mock) {
            $mock->shouldReceive('getType')->once()->andReturn('no-giro');
        });

        $creditor = m::mock('byrokrat\billing\LegalPerson', function ($mock) use ($account) {
            $mock->shouldReceive('getAccount')->once()->andReturn($account);
        });

        $this->setExpectedException('byrokrat\autogiro\Exception\LogicException');
        (new BankgiroFormatter)->format($creditor);
    }

    public function testFormat()
    {
        $account = m::mock('byrokrat\banking\Bankgiro', function ($mock) {
            $mock->shouldReceive('getNumber')->once()->andReturn('111-1111');
        });

        $creditor = m::mock('byrokrat\billing\LegalPerson', function ($mock) use ($account) {
            $mock->shouldReceive('getAccount')->once()->andReturn($account);
        });

        $this->assertSame(
            '0001111111',
            (new BankgiroFormatter)->format($creditor)
        );
    }
}
