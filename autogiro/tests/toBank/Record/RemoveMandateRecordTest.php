<?php

namespace byrokrat\autogiro\toBank\Record;

use Mockery as m;

class RemoveMandateRecordTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateRemoveMandateRecord()
    {
        $creditor = m::mock('byrokrat\billing\LegalPerson');

        $debtor = m::mock('byrokrat\billing\LegalPerson', function ($mock) {
            $mock->shouldReceive('getAccount->to16')->once()->andReturn('CCCC00000000000N');
        });

        $formatters = m::mock('byrokrat\autogiro\toBank\Record\Formatters', function ($mock) {
            $mock->shouldReceive('getBankgiroFormatter->format')->once()->andReturn('NNNNNNNNNN');
            $mock->shouldReceive('getPayerNumberFormatter->format')->once()->andReturn('1111111111111111');
        });

        $strRecord = (new RemoveMandateRecord($creditor, $debtor, $formatters))->getRecord();

        $this->assertRegExp(
            '/^03NNNNNNNNNN1111111111111111CCCC00000000000N\s{36}$/',
            $strRecord
        );

        $this->assertSame(
            80,
            strlen($strRecord)
        );
    }
}
