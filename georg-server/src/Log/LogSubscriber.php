<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\georg\Log;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Monolog\Logger;
use ledgr\georg\Events;
use ledgr\georg\DonorEvent;

/**
 * Logg events using monolog
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class LogSubscriber implements EventSubscriberInterface
{
    /**
     * @var Logger Monolog logger
     */
    private $logger;

    /**
     * Log events using monolog
     *
     * @param Logger $logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Get list of event types to register
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            //Events::APPROVED_CONSENT => 'onApproveConsent',
            Events::REQUEST_CONSENT => 'onRequestConsent',
            Events::BILL_ONCE => 'onBillOnce',
        );
    }

    /**
     * Log requested consent
     *
     * @param  DonorEvent $event
     * @return void
     */
    public function onRequestConsent(DonorEvent $event)
    {
        $this->logger->addInfo(
            'Medgivande skickat till bank',
            array('donorId' => $event->getDonor()->getPersonalId()->getId())
        );
    }

    /**
     * Log bill
     *
     * @param  DonorEvent $event
     * @return void
     */
    public function onBillOnce(DonorEvent $event)
    {
        $this->logger->addInfo(
            "Fakturerade {$event->getDonor()->getAmount()} kr",
            array('donorId' => $event->getDonor()->getPersonalId()->getId())
        );
    }
}
