<?php

namespace ledgr\autogiro\toBank\Record;

use Mockery as m;

class OpeningRecordTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateOpeningRecord()
    {
        $creditor = m::mock('ledgr\billing\LegalPerson', function ($mock) {
            $mock->shouldReceive('getCustomerNumber')->once()->andReturn('C');
        });

        $formatters = m::mock('ledgr\autogiro\toBank\Record\Formatters', function ($mock) {
            $mock->shouldReceive('getBankgiroFormatter->format')->once()->andReturn('NNNNNNNNNN');
        });

        $strRecord = (new OpeningRecord($creditor, new \DateTime, $formatters))->getRecord();

        $this->assertRegExp(
            '/^01\d{8}AUTOGIRO\s{44}00000CNNNNNNNNNN  $/',
            $strRecord
        );

        $this->assertSame(
            80,
            strlen($strRecord)
        );
    }
}
