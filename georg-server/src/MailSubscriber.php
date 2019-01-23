<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\georg;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Send information to donors
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 * @todo   Implement mailing
 */
class MailSubscriber implements EventSubscriberInterface
{
    /**
     * Get list of event types to register
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            Events::APPROVED_CONSENT => 'onApproveConsent'
        );
    }

    /**
     * Notify observer that event has taken place
     *
     * @param  DonorEvent $event
     * @return void
     */
    public function onApproveConsent(DonorEvent $event)
    {
        // Send welcome mail
        // via event kan jag hämta dispatcher och skicka nya events..
    }
}
