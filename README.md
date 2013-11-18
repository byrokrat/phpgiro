autogiro
========

Create and parse files for the swedish autogiro system.

Autogiro is a concrete implementation of [iio/giro](https://github.com/iio/giro).
Please note that all exceptions thrown are subclasses of `iio\giro\Exception`.

Autogiro is still in beta stage.


Installation using composer
---------------------------
Simply add `iio/autogiro` to your list of required libraries.


Parsing autogiro files
----------------------
    use iio\giro\Giro;
    use iio\autogiro\AutogiroFactory;

    $giro = new Giro(new AutogiroFactory);
    $file = file('tests/samples/new/nya-medgivanden-internetbank.txt');
    $domDocument = $giro->convertToXML($file);


Creating autogiro files
-----------------------
    use iio\autogiro\Builder\AutogiroBuilder;
    use iio\autogiro\Builder\Organization;
    use iio\giro\Giro;
    use iio\autogiro\AutogiroFactory;

    $giro = new Giro(new AutogiroFactory);

    $org = new Organization();
    //... set organization data

    $builder = new AutogiroBuilder($giro, $org);

    $builder->addConsent(...);
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


Continuous integration
----------------------
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/iio/autogiro/badges/quality-score.png?s=37bf9f28d789e5b84b58218fb3931df64c648898)](https://scrutinizer-ci.com/g/iio/autogiro/)
[![Code Coverage](https://scrutinizer-ci.com/g/iio/autogiro/badges/coverage.png?s=597d13d586ba95cb3685b405e6f1371f45835478)](https://scrutinizer-ci.com/g/iio/autogiro/)

Installing dependencies, running tests and other code analysis tools can be
handled using `phing`. To run CI tests type `phing` from the project root
directory, point your browser to `build/index.html` to view the results.
