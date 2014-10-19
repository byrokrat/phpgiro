<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\autogiro\Post;

use ledgr\autogiro\Builder\ConsentPostBuilder;
use ledgr\billing\LegalPerson;

/**
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class ConsentParser extends ConsentPostBuilder
{
    public function __construct($raw)
    {
        // TODO implement parsing
        // when done $raw == (new ConsentParser($raw))->getRaw()
        // whould validate that parsing and building works as intended
        // (given that $raw is valid in the first place)

        // parse raw into LegalPerson
        $person = '';

        parent::__construct($person);
    }
}
