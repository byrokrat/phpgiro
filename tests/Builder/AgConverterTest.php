<?php
namespace iio\swegiro\Builder;

use iio\stb\Banking\Bankgiro;
use iio\stb\ID\PersonalId;
use Mockery as m;

class AgConverterTest extends \PHPUnit_Framework_TestCase
{
    public function testConvertPayerNr()
    {
        $converter = new AgConverter();
        $id = new PersonalId('120101-0004');
        $this->assertEquals('1201010004', $converter->convertPayerNr($id));
    }

    public function testConvertId()
    {
        $converter = new AgConverter();
        $id = new PersonalId('120101-0004');
        $this->assertEquals('201201010004', $converter->convertId($id));
    }

    public function testConvertBankgiro()
    {
        $converter = new AgConverter();
        $bg = new Bankgiro('111-1111');
        $this->assertEquals('1111111', $converter->convertBankgiro($bg));
    }
}
