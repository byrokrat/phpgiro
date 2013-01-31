<?php
namespace iio\swegiro\AG\Nya;

class F extends \iio\swegiro\AG\F
{
    public function __construct($customerNr = false, $bg = false)
    {
        parent::__construct($customerNr, $bg);
        $this->map['01'] = array("/^01AUTOGIRO.{12}..(\d{8}).{12}AVVISADE BET UPPDR.{2}(\d{6})(\d{10})\s*$/", 'parseHeadDateCustBg');
    }
}
