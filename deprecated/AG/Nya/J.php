<?php
namespace itbz\swegiro\AG\Nya;

class J extends \itbz\swegiro\AG\J
{
    public function __construct($customerNr = false, $bg = false)
    {
        parent::__construct($customerNr, $bg);
        $this->regexp = array('/^(\d{10})(\d{12})(\d{16})(\d)(\d\d)(\d{8})(.{8})(\d)(.?)(.{0,5})(\d{0,4})(\d{0,12})/', 'parseConsent');
        $this->setMap();
    }
}
