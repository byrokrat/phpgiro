<?php
namespace iio\swegiro\AG\Nya;

class G extends \iio\swegiro\AG\G
{
    public function __construct($customerNr = false, $bg = false)
    {
        parent::__construct($customerNr, $bg);
        $this->map['01'] = array(utf8_decode("/^01AUTOGIRO.{12}..(\d{8}).{12}MAKULERING\/ÄNDRING..(\d{6})(\d{10})/"), 'parseHeadDateCustBg');
    }
}
