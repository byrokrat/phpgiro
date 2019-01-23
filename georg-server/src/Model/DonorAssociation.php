<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\georg\Model;

use Model;
use ledgr\id\PersonalId;

/**
 * Defines getId(), setId(), getDonorId(), setDonorId() and donor() for models associated to donor
 * 
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class DonorAssociation extends Model
{
    /**
     * Paris association
     *
     * This model belongs to Donor
     *
     * @return Model
     */
    public function donor()
    {
        return $this->belongs_to('Donor');
    }

    /**
     * Associate this record with donor
     *
     * @param  Donor $associatedRecord
     * @return void
     */
    public function associateWith(Donor $associatedRecord)
    {
        $this->setDonorId($associatedRecord->getPersonalId());
    }

    /**
     * Get model id
     *
     * @return string
     */
    public function getId()
    {
        return isset($this->id) ? $this->id : '';
    }

    /**
     * Set model id
     *
     * @param  string $id
     * @return void
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get id of donor
     *
     * @return PersonalId
     */
    public function getDonorId()
    {
        return new PersonalId($this->donor_id);
    }

    /**
     * Set id of donor
     *
     * @param  PersonalId $id
     * @return Donor instance for chaining
     */
    public function setDonorId(PersonalId $id)
    {
        $this->donor_id = $id->getId();
        return $this;
    }
}
