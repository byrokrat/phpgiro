<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\autogiro\Builder;

/**
 * Reject online consent application
 *
 * Use to reject an online consent application. To approve an online application
 * use the regular ConsentBuilder.
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class RejectedConsentBuilder extends ConsentBuilder
{
    public function getRaw()
    {
        return substr(parent::getRaw(), 0, 76) . 'AV  ';
    }
}
