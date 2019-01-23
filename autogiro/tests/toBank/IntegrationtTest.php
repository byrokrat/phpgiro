<?php

namespace byrokrat\autogiro\toBank;

use Mockery as m;

class IntegrationtTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateMandateFile()
    {
        $this->markTestSkipped('Test requires an outdated version of the billing package.');

        // TODO document this usage pattern once the new billing package is in use
            // there might be changes in usage of LegalPerson

        // TODO document (and test) the usage of the setFormatter methods
            // to change to payer number structure..

        $creditor = new \byrokrat\billing\LegalPerson(
            'creditor',
            new \byrokrat\id\CorporateId('222222-2222'),
            new \byrokrat\banking\Bankgiro('111-1111'),
            '9999'
        );

        $debtor = new \byrokrat\billing\LegalPerson(
            'debtor',
            new \byrokrat\id\PersonalId('222222-2225'),
            new \byrokrat\banking\NordeaPerson('3300,2222222222')
        );

        $mandates = new MandateFile($creditor);
        $mandates->remove($debtor);

        $this->assertTrue(!!$mandates->getContents());
    }
}
