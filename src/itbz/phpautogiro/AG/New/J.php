<?php
/**
 * This file is part of the STB package
 *
 * Copyright (c) 2011-12 Hannes Forsgård
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Hannes Forsgård <hannes.forsgard@gmail.com>
 *
 * @package STB\Giro\Ag
 */


/**
 * AG layout J, list of consents registered with the bank. New file layout.
 *
 * @package STB\Giro\Ag
 */
class PhpGiro_AG_New_J extends PhpGiro_AG_J
{

    /**
     * Set parsing regexp for the new file layout
     *
     * @param string $customerNr
     *
     * @param string $bg
     */
    public function __construct($customerNr = FALSE, $bg = FALSE)
    {
        parent::__construct($customerNr, $bg);
        $this->regexp = array('/^(\d{10})(\d{12})(\d{16})(\d)(\d\d)(\d{8})(.{8})(\d)(.?)(.{0,5})(\d{0,4})(\d{0,12})/', 'parseConsent');
        $this->setMap();
    }

}
