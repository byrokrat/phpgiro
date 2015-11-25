<?php

namespace byrokrat\autogiro\toBank\Record;

use Mockery as m;

class RejectMandateRecordTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateRejectMandateRecord()
    {
        $creditor = m::mock('byrokrat\billing\LegalPerson');

        $debtor = m::mock('byrokrat\billing\LegalPerson', function ($mock) {
            $mock->shouldReceive('getAccount->to16')->once()->andReturn('CCCC00000000000N');
        });

        $formatters = m::mock('byrokrat\autogiro\toBank\Record\Formatters', function ($mock) {
            $mock->shouldReceive('getBankgiroFormatter->format')->once()->andReturn('NNNNNNNNNN');
            $mock->shouldReceive('getPayerNumberFormatter->format')->once()->andReturn('1111111111111111');
            $mock->shouldReceive('getIdFormatter->format')->once()->andReturn('222222222222');
        });

        $strRecord = (new RejectMandateRecord($creditor, $debtor, $formatters))->getRecord();

        $this->assertRegExp(
            '/^04NNNNNNNNNN1111111111111111CCCC00000000000N222222222222\s{20}AV  $/',
            $strRecord
        );

        $this->assertSame(
            80,
            strlen($strRecord)
        );
    }
}
