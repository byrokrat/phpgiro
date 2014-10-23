<?php

namespace ledgr\autogiro\toBank;

use Mockery as m;

class IntegrationtTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateMandateFile()
    {
        // TODO document this usage pattern once the new billing package is in use
            // there might be changes in usage of LegalPerson

        // TODO document (and test) the usage of the setFormatter methods
            // to change to payer number structure..

        $creditor = new \ledgr\billing\LegalPerson(
            'creditor',
            new \ledgr\id\CorporateId('222222-2222'),
            new \ledgr\banking\Bankgiro('111-1111'),
            '9999'
        );

        $debtor = new \ledgr\billing\LegalPerson(
            'debtor',
            new \ledgr\id\PersonalId('222222-2225'),
            new \ledgr\banking\NordeaPerson('3300,2222222222')
        );

        $mandates = new MandateFile($creditor);
        $mandates->remove($debtor);

        $this->assertTrue(!!$mandates->getContents());
    }
}
