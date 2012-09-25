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
 * AG layout D, feedback on completed transactions.
 *
 * @package STB\Giro\Ag
 */
class PhpGiro_AG_D extends PhpGiro_AG_Object
{

    /**
     * Layout id
     * @var string $layout
     */
    protected $layout = 'D';


    /**
     * Regex represention a valid file structure
     * @var string $struct
     */
    protected $struct = "/^(01([83]2)+09)+$/";


    /**
     * Map transaction codes (TC) to line-parsing regexp and receiving method
     * @var array $map
     */
    protected $map = array(
        '01' => array("/^01(\d{8})AUTOGIRO9900.{40}(\d{6})(\d{10})/", 'parseHeadDateCustBg'),
        '82' => array("/^([83]2)(.{8})(.)(.{3}) (\d{16})(\d{12})(\d{10})(.{0,16}).{0,10}(.)?\s*$/", 'parseTransaction'),
        '32' => array("/^([83]2)(.{8})(.)(.{3}) (\d{16})(\d{12})(\d{10})(.{0,16}).{0,10}(.)?\s*$/", 'parseTransaction'),
        '09' => array("/^09(\d{8})9900.{14}(\d{12})(\d{6})(\d{6})0{4}(\d{12})0*$/", 'parseTransactionFoot'),
    );

}
