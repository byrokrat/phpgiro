<?php

namespace iio\swegiro;

use iio\swegiro\Factory\AgFactory;
use iio\swegiro\Builder\AgBuilder;
use iio\swegiro\Organization;
use iio\stb\Banking\Bankgiro;
use iio\swegiro\ID\PersonalId;
use iio\stb\Banking\NordeaPerson;

include "vendor/autoload.php";

$giro = new Swegiro(new AgFactory);


// Stub generate native content
$org = new Organization();
$org->setBankgiro(new Bankgiro('111-1111'));
$org->setAgCustomerNumber('123456');

$builder = new AgBuilder($giro, $org);

$builder->addConsent(new PersonalId('820323-0258'), new NordeaPerson('3300,8203230258'));

header('Content-Type: text/plain');
echo $builder->getNative();


// Stub parse 
//$file = file('tests/unit/samples/new/nya-medgivanden-internetbank.txt');
//$dom = $giro->convertToXML($file);

// skriv ut xml...
