<?php

namespace byrokrat\autogiro\toBank;

use Mockery as m;

class FileTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateInstance()
    {
        $creditor = m::mock('byrokrat\billing\LegalPerson');

        $formatters = m::mock('byrokrat\autogiro\toBank\Record\Formatters');

        $fileObj = m::mock('byrokrat\autogiro\FileObject', function($mock) {
            $mock->shouldReceive('addLine')->once();
            $mock->shouldReceive('getContents')->once()->andReturn('contents');
        });

        $openingRecord = m::mock('byrokrat\autogiro\toBank\Record', function($mock) {
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
