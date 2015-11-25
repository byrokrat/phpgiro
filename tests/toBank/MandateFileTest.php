<?php

namespace byrokrat\autogiro\toBank;

use Mockery as m;

class MandateFileTest extends \PHPUnit_Framework_TestCase
{
    private function getMandateFile()
    {
        return m::mock('byrokrat\autogiro\toBank\MandateFile', function ($mock) {
            $mock->shouldReceive('addRecord')->once();
            $mock->shouldReceive('getCreditor')->once()->andReturn(m::mock('byrokrat\billing\LegalPerson'));
            $mock->shouldReceive('getFormatters')->once()->andReturn(m::mock('byrokrat\autogiro\toBank\Record\Formatters'));
        })->makePartial();
    }

    public function testRemove()
    {
        $this->getMandateFile()->remove(m::mock('byrokrat\billing\LegalPerson'));
    }

    public function testRegister()
    {
        $this->getMandateFile()->register(m::mock('byrokrat\billing\LegalPerson'));
    }

    public function testApprove()
    {
        $this->getMandateFile()->approve(m::mock('byrokrat\billing\LegalPerson'));
    }

    public function testReject()
    {
        $this->getMandateFile()->reject(m::mock('byrokrat\billing\LegalPerson'));
    }
}
