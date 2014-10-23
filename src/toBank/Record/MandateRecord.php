<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\autogiro\toBank\Record;

use ledgr\billing\LegalPerson;

/**
 * Mandate base record
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
abstract class MandateRecord implements \ledgr\autogiro\toBank\Record
{
    /**
     * @var LegalPerson Payment recipient
     */
    protected $creditor;

    /**
     * @var LegalPerson Payment sender
     */
    protected $debtor;

    /**
     * @var Formatters Collection of formatters
     */
    protected $formatters;

    /**
     * Create record
     *
     * @param LegalPerson $creditor   Payment recipient
     * @param LegalPerson $debtor     Payment sender
     * @param Formatters  $formatters Collection of formatters
     */
    public function __construct(LegalPerson $creditor, LegalPerson $debtor, Formatters $formatters)
    {
        $this->creditor = $creditor;
        $this->debtor = $debtor;
        $this->formatters = $formatters;
    }
}
