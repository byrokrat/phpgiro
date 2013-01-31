<?php

namespace iio\swegiro;

use iio\swegiro\Factory\AgFactory;

include "vendor/autoload.php";
header('Content-Type: text/plain');

$giro = new Swegiro(new AgFactory);
$file = file('tests/unit/samples/new/nya-medgivanden-internetbank.txt');

$dom = $giro->convertToXML($file);

// skriv ut xml...
