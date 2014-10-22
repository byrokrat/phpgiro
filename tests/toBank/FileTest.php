<?php

namespace ledgr\autogiro\toBank;

use Mockery as m;

class FileTest extends \PHPUnit_Framework_TestCase
{
    public function testAddLine()
    {
        $file = new File(
            m::mock('ledgr\billing\LegalPerson'),
            m::mock('ledgr\autogiro\FileObject')->shouldReceive('addLine')->mock()
        );
        $file->addLine('');
    }

    public function testGetContents()
    {
        $file = new File(
            m::mock('ledgr\billing\LegalPerson'),
            m::mock('ledgr\autogiro\FileObject')->shouldReceive('getContents')->once()->andReturn('contents')->mock()
        );
        $this->assertSame(
            'contents',
            $file->getContents()
        );
    }

    public function testGetCreditor()
    {
        $creditor = m::mock('ledgr\billing\LegalPerson');
        $file = new File(
            $creditor,
            m::mock('ledgr\autogiro\FileObject')
        );
        $this->assertSame(
            $creditor,
            $file->getCreditor()
        );
    }

    public function testCreateSectionHeader()
    {
        $creditor = m::mock('ledgr\billing\LegalPerson');
        $creditor->shouldReceive('getCustomerNumber')->andReturn('C');
        $creditor->shouldReceive('getAccount')->andReturn('A');

        $file = new File($creditor);

        $this->assertRegExp(
            '/^01\d{8}AUTOGIRO\s{44}00000C000000000A\s{2}\r\n$/',
            $file->getContents()
        );
    }
}
