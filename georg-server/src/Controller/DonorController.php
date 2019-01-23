<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\georg\Controller;

use inroute\Route;
use ledgr\id\PersonalId;
use ledgr\georg\Model\Donor;
use Symfony\Component\HttpFoundation\Response;

/**
 * Georg donor controller
 * 
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 * @controller </donors>
 */
class DonorController
{
    /**
     * Add donor to database
     *
     * @param  Route $route
     * @return Response
     * @route  POST </>
     */
    public function addDonor(Route $route)
    {
        // TODO använd inte $_POST utan request object (skickas med som arg hit..)

        // TODO att skapa $donor objekt och fylla dem med request data är ett jobb
            // för en Builder eller Factory (eller vad det nu ska kallas...)
            // där ska validering ske
            // det gör också att jag enklare kan skriva test för att valideringen är som den ska

        // TODO validera och filtrera input

        $donor = \Model::factory('Donor')->create();
        $donor->setPersonalId(new PersonalId($_POST['id']));
        $donor->setGivenName($_POST['given_name']);
        $donor->setSurname($_POST['surname']);
        $donor->setAccount(\iio\stb\Banking\StaticAccountBuilder::build($_POST['account']));
        $donor->setAmount(new \iio\stb\Utils\Amount($_POST['amount']));
        $donor->setRegisterWithBank();
        $donor->setNotes($_POST['notes']);

        $mail = $donor->mail()->create();
        $mail->associateWith($donor);
        $mail->setMail($_POST['mail_mail']);
        $mail->setNotes($_POST['mail_notes']);

        $address = $donor->address()->create();
        $address->associateWith($donor);
        $address->street = $_POST['address_street'];
        $address->plot = $_POST['address_plot'];
        $address->postcode = $_POST['address_postcode'];
        $address->town = $_POST['address_town'];
        $address->country = $_POST['address_country'];
        $address->setNotes($_POST['address_notes']);

        // TODO felhantering om $donor redan finns...
        $donor->save();
        $mail->save();
        $address->save();

        // TODO $url ska genereras as Routerns generera url funktion!!
        $url = "/donors/{$donor->getPersonalId()->getId()}";

        // TODO ska göra en re-route till readDonor() egentligen...
        return new HalResponse($url, $donor);
    }

    /**
     * Read donor information
     *
     * @param  Route $route
     * @return Response
     * @route  GET </{:donorId}>
     */
    public function readDonor(Route $route)
    {
        echo $route->getValue('donorId');
    }

    /**
     * Get list of donors
     *
     * @param  Route $route
     * @return Response
     * @route  GET <>
     */
    public function readDonorList(Route $route)
    {
        echo "read donor";
    }

    /**
     * Update donor information
     *
     * @param  Route $route
     * @return Response
     * @route  PUT </{:donorId}>
     */
    public function updateDonor(Route $route)
    {
    }

    /**
     * Remove donor
     *
     * @param  Route $route
     * @return Response
     * @route  DELETE </{:donorId}>
     */
    public function deleteDonor(Route $route)
    {
    }
}
