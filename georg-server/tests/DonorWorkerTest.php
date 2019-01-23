<?php
namespace ledgr\georg;

use Mockery as m;

class DonorWorkerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException ledgr\georg\Exception\AutogiroException
     */
    public function testProcessNoDataException()
    {
        $agBuilder = m::mock('\ledgr\autogiro\Builder\AutogiroBuilder')->shouldIgnoreMissing();
        $agBuilder->shouldReceive('getNative')->andThrow(new \ledgr\giro\Exception);

        $dispatcher = m::mock('\Symfony\Component\EventDispatcher\EventDispatcher');

        $worker = new DonorWorker($agBuilder, $dispatcher);
        $worker->process(array());
    }

    public function testProcessNewConsent()
    {
        $donor = m::mock('\ledgr\georg\Model\Donor');
        $donor->shouldReceive('isRegisterWithBank')->once()->andReturn(true);
        $donor->shouldReceive('getGivenName')->once()->andReturn('N');
        $donor->shouldReceive('getSurname')->once()->andReturn('N');
        $donor->shouldReceive('getAccount')->once()->andReturn(m::mock('\ledgr\banking\UnknownAccount'));
        $donor->shouldReceive('getPersonalId')->once()->andReturn(m::mock('\ledgr\id\PersonalId'));
        $donor->shouldReceive('setWaiting')->once()->andReturn(null);
        $donor->shouldReceive('save')->once();

        $agBuilder = m::mock('\ledgr\autogiro\Builder\AutogiroBuilder');
        $agBuilder->shouldReceive('clear')->once();
        $agBuilder->shouldReceive('addConsent')->once();
        $agBuilder->shouldReceive('getNative')->once()->andReturn('newConsent');

        $worker = new DonorWorker(
            $agBuilder,
            m::mock('\Symfony\Component\EventDispatcher\EventDispatcher')->shouldIgnoreMissing()
        );

        $this->assertEquals('newConsent', $worker->process(array($donor)));
    }

    public function testBillAll()
    {
        $donor = m::mock('\ledgr\georg\Model\Donor');
        $donor->shouldReceive('isAutogiro')->once()->andReturn(true);
        $donor->shouldReceive('getPersonalId')->once()->andReturn(m::mock('\ledgr\id\PersonalId'));
        $donor->shouldReceive('getAmount')->once()->andReturn(m::mock('\ledgr\amount\Amount'));

        $agBuilder = m::mock('\ledgr\autogiro\Builder\AutogiroBuilder');
        $agBuilder->shouldReceive('clear')->once();
        $agBuilder->shouldReceive('bill')->once();
        $agBuilder->shouldReceive('getNative')->once()->andReturn('billAll');

        $worker = new DonorWorker(
            $agBuilder,
            m::mock('\Symfony\Component\EventDispatcher\EventDispatcher')->shouldIgnoreMissing()
        );

        $date = new \DateTime;
        $date->add(new \DateInterval('P1D'));

        $this->assertEquals('billAll', $worker->billAll(array($donor), $date));
    }

    /**
     * @expectedException ledgr\georg\Exception\BillException
     */
    public function testBillAllInvalidBillDate()
    {
        $worker = new DonorWorker(
            m::mock('\ledgr\autogiro\Builder\AutogiroBuilder'),
            m::mock('\Symfony\Component\EventDispatcher\EventDispatcher')
        );
        $worker->billAll(array(), new \DateTime);
    }
}
