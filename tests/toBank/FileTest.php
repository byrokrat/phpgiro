<?php

namespace ledgr\autogiro\toBank;

use Mockery as m;

class FileTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateInstance()
    {
        $creditor = m::mock('ledgr\billing\LegalPerson');

        $formatters = m::mock('ledgr\autogiro\toBank\Record\Formatters');

        $fileObj = m::mock('ledgr\autogiro\FileObject', function($mock) {
            $mock->shouldReceive('addLine')->once();
            $mock->shouldReceive('getContents')->once()->andReturn('contents');
        });

        $openingRecord = m::mock('ledgr\autogiro\toBank\Record', function($mock) {
            $mock->shouldReceive('getRecord')->once();
        });

        $file = new File($creditor, new \DateTime, $formatters, $fileObj, $openingRecord);

        $this->assertSame(
            $creditor,
            $file->getCreditor()
        );

        $this->assertSame(
            $formatters,
            $file->getFormatters()
        );

        $this->assertSame(
            'contents',
            $file->getContents()
        );
    }
}
