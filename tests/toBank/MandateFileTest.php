<?php

namespace ledgr\autogiro\toBank;

use Mockery as m;

class MandateFileTest extends \PHPUnit_Framework_TestCase
{
    private function getMandateFile()
    {
        return m::mock('ledgr\autogiro\toBank\MandateFile', function ($mock) {
            $mock->shouldReceive('addRecord')->once();
            $mock->shouldReceive('getCreditor')->once()->andReturn(m::mock('ledgr\billing\LegalPerson'));
            $mock->shouldReceive('getFormatters')->once()->andReturn(m::mock('ledgr\autogiro\toBank\Record\Formatters'));
        })->makePartial();
    }

    public function testRemove()
    {
        $this->getMandateFile()->remove(m::mock('ledgr\billing\LegalPerson'));
    }

    public function testRegister()
    {
        $this->getMandateFile()->register(m::mock('ledgr\billing\LegalPerson'));
    }

    public function testApprove()
    {
        $this->getMandateFile()->approve(m::mock('ledgr\billing\LegalPerson'));
    }

    public function testReject()
    {
        $this->getMandateFile()->reject(m::mock('ledgr\billing\LegalPerson'));
    }
}
