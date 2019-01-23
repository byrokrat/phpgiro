<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\georg;

use ledgr\georg\Exception\BillException;
use ledgr\georg\Exception\AutogiroException;
use ledgr\autogiro\Builder\AutogiroBuilder;
use ledgr\billing\LegalPerson;
use Symfony\Component\EventDispatcher\EventDispatcher;
use DateTime;

/**
 * Donor worker
 *
 * It is the responsability of the donor worker to process donors and create
 * adequate autogiro files depending on donor payment terms.
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class DonorWorker
{
    /**
     * @var AutogiroBuilder Creates the autogiro data
     */
    private $agBuilder;

    /**
     * @var EventDispatcher Dispatcher
     */
    private $dispatcher;

    /**
     * Donor worker
     *
     * @param AutogiroBuilder $agBuilder
     * @param EventDispatcher $dispatcher
     */
    public function __construct(AutogiroBuilder $agBuilder, EventDispatcher $dispatcher)
    {
        $this->agBuilder = $agBuilder;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Process list of donors
     *
     * Actions taken on the basis of each donor's payment terms
     *
     * @param  array  $donors Array of Donor objects
     * @return string The created autogiro file
     * @throws AutogiroException If autogiro file could not be created
     */
    public function process(array $donors)
    {
        $this->agBuilder->clear();
        
        // TODO när periodic bill updateras så måste ett datum sättas på något sätt...

        /** @var \ledgr\georg\Model\Donor $donor */
        foreach ($donors as $donor) {
            if ($donor->isRegisterWithBank()) {
                // TODO Donor ska vara en typ av LegalPerson istället! (Kräver updatering av billing..)
                // så jag kan skriva: $this->agBuilder->addConsent($donor);

                $legalPerson = new LegalPerson(
                    $donor->getGivenName() . ' ' . $donor->getSurname(),
                    $donor->getPersonalId(),
                    $donor->getAccount()
                );

                $this->agBuilder->addConsent($legalPerson);
                $donor->setWaiting();
                $donor->save();
                $this->dispatcher->dispatch(Events::REQUEST_CONSENT, new DonorEvent($donor));
            }
        }

        return $this->buildNative();
    }

    /**
     * Bill all active donors once
     *
     * Active donors are billed their designated amount at $billDate
     * 
     * @param  array    $donors   List of Donor objects
     * @param  DateTime $billDate The date billing will be performed
     * @return string   The created autogiro file
     * @throws AutogiroException If autogiro file could not be created
     * @throws BillException     If date is not at least 24 hours in the future
     */
    public function billAll(array $donors, DateTime $billDate)
    {
        if (144 > $billDate->getTimestamp() - time()) {
            throw new BillException('Bill date must be at least 24 hours in the future');
        }

        $this->agBuilder->clear();

        /** @var \ledgr\georg\Model\Donor $donor */
        foreach ($donors as $donor) {
            if ($donor->isAutogiro()) {
                $this->agBuilder->bill(
                    $donor->getPersonalId(),
                    $donor->getAmount(),
                    $billDate
                );
                $this->dispatcher->dispatch(Events::BILL_ONCE, new DonorEvent($donor));
            }
        }

        return $this->buildNative();
    }

    /**
     * Build AG-file and return native content
     *
     * @return string The created autogiro file
     * @throws AutogiroException If autogiro file could not be created
     */
    private function buildNative()
    {
        try {
            return $this->agBuilder->getNative();
        } catch (\ledgr\giro\Exception $e) {
            throw new AutogiroException('Unable to create autogiro file', 0, $e);
        }
    }
}
