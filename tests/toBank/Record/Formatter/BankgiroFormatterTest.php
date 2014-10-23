<?php

namespace ledgr\autogiro\toBank\Record\Formatter;

use Mockery as m;

class BankgiroFormatterTest extends \PHPUnit_Framework_TestCase
{
    public function testFormatNoBankgiroException()
    {
        $account = m::mock('ledgr\banking\BankAccountInterface', function ($mock) {
            $mock->shouldReceive('getType')->once()->andReturn('no-giro');
        });

        $creditor = m::mock('ledgr\billing\LegalPerson', function ($mock) use ($account) {
            $mock->shouldReceive('getAccount')->once()->andReturn($account);
        });

        $this->setExpectedException('ledgr\autogiro\Exception\LogicException');
        (new BankgiroFormatter)->format($creditor);
    }

    public function testFormat()
    {
        $account = m::mock('ledgr\banking\Bankgiro', function ($mock) {
            $mock->shouldReceive('getNumber')->once()->andReturn('111-1111');
        });

        $creditor = m::mock('ledgr\billing\LegalPerson', function ($mock) use ($account) {
            $mock->shouldReceive('getAccount')->once()->andReturn($account);
        });

        $this->assertSame(
            '0001111111',
            (new BankgiroFormatter)->format($creditor)
        );
    }
}
