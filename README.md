# ledgr/autogiro [![Build Status](https://travis-ci.org/ledgr/autogiro.svg?branch=master)](https://travis-ci.org/ledgr/autogiro) [![Code Coverage](https://scrutinizer-ci.com/g/ledgr/autogiro/badges/coverage.png?s=33c8ab5cc95b00f2e9f5493830116e96a4866ba5)](https://scrutinizer-ci.com/g/ledgr/autogiro/) [![Dependency Status](https://gemnasium.com/ledgr/autogiro.svg)](https://gemnasium.com/ledgr/autogiro)


Create and parse files for the swedish autogiro system.

Autogiro is a concrete implementation of [ledgr/giro](https://github.com/ledgr/giro).
Please note that all exceptions thrown are subclasses of `ledgr\giro\Exception`.


Installation using composer
---------------------------
Simply add `ledgr/autogiro` to your list of required libraries.


Parsing autogiro files
----------------------
    use ledgr\giro\Giro;
    use ledgr\autogiro\AutogiroFactory;

    $giro = new Giro(new AutogiroFactory);
    $file = file('tests/samples/new/nya-medgivanden-internetbank.txt');
    $domDocument = $giro->convertToXML($file);


Creating autogiro files
-----------------------
    use ledgr\autogiro\Builder\AutogiroBuilder;
    use ledgr\billing\LegalPerson;

    $org = new LegalPerson(...);
    $builder = new AutogiroBuilder($org);
    $builder->addConsent(new LegalPerson(...));
    echo $builder->getNative();


Supported file formats
----------------------
Layouts A - H in the legacy Autogiro Privat.

Layouts A - C and E - J in new Autogiro (in use fall 2011). (Support for
layout D is currently missing, but the BgMax format can be used instead.)

Bankgirot standard format BgMax

[PlusGirot layout N (also known as 02P)]

    +=============================+=====+=====+=====+
    | LAYOUT                      | PRI | OLD | NEW |
    +=============================+=====+=====+=====+
    | A (Medgivandeunderlag)      |  X  |  X  |  X  |
    +-----------------------------+-----+-----+-----+
    | B (Betalningsunderlag)      |  X  |  X  |  X  |
    +-----------------------------+-----+-----+-----+
    | C (Mak./ändr. bet.underlag) |  X  |  X  |  X  |
    +-----------------------------+-----+-----+-----+
    | D (Betalningsspec.)         |  X  |  X  |  ?  |
    +-----------------------------+-----+-----+-----+
    | BGMAX (Betalningsspec.)     |  -  |  -  |  X  |
    +-----------------------------+-----+-----+-----+
    | E (Medgivandeavisering)     |  X  |  X  |  X  |
    +-----------------------------+-----+-----+-----+
    | F (Avvisade bet.)           |  X  |  X  |  X  |
    +-----------------------------+-----+-----+-----+
    | G (Mak./ändrings-lista)     |  X  |  X  |  X  |
    +-----------------------------+-----+-----+-----+
    | H (Elektr. medgivanden)     |  X  |  X  |  X  |
    +-----------------------------+-----+-----+-----+
    | I (Utdrag bevakningsreg)    |  -  |  X  |  X  |
    +-----------------------------+-----+-----+-----+
    | J (Utdrag medgivandereg)    |  -  |  X  |  X  |
    +=============================+=====+=====+=====+
      PRI = autogiro privat
      OLD = new autogiro with old layout
      NEW = nya autogiro with new layout


Run tests
---------
Execute unit tests by typing `phpunit`. The unis tests requires that dependencies
are installed using composer.

    $ curl -sS https://getcomposer.org/installer | php
    $ php composer.phar install
    $ phpunit
