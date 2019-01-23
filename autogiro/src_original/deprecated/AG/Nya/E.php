<?php
namespace iio\swegiro\AG\Nya;

class E extends \iio\swegiro\AG\E
{
    public function __construct($customerNr = false, $bg = false)
    {
        parent::__construct($customerNr, $bg);
        $this->map['01'] = array("/^01AUTOGIRO.{12}..(\d{8}).{12}AG-MEDAVI.{11}(\d{6})(\d{10})\s*$/", 'parseHeadDateCustBg');
    }
}
