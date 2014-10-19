<?php
namespace ledgr\autogiro\Builder;

use ledgr\banking\Bankgiro;
use ledgr\id\PersonalId;
use Mockery as m;

class AutogiroConverterTest extends \PHPUnit_Framework_TestCase
{
    public function testConvertPayerNr()
    {
        $converter = new AutogiroConverter();
        $id = new PersonalId('120101-0004');
        $this->assertEquals('1201010004', $converter->convertPayerNr($id));
    }

    public function testConvertId()
    {
        $converter = new AutogiroConverter();
        $id = new PersonalId('120101-0004');
        $this->assertEquals('201201010004', $converter->convertId($id));
    }

    public function testConvertBankgiro()
    {
        $converter = new AutogiroConverter();
        $bg = new Bankgiro('111-1111');
        $this->assertEquals('1111111', $converter->convertBankgiro($bg));
    }
}
