<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\georg;

use inroute\ContainerInterface;
use ledgr\georg\Model\Settings;

/**
 * Georg DI-container
 * 
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class Container extends \Pimple implements ContainerInterface
{
    /**
     * Georg DI-container
     */
    public function __construct()
    {
        $this['donorMapper'] = $this->share(
            function ($c) {
                return \Model::Factory('Donor');
            }
        );

        $this['donorWorker'] = $this->share(
            function ($c) {
                return new DonorWorker($c['autogiroBuilder'], $c['dispatcher']);
            }
        );

        $this['dispatcher'] = $this->share(
            function ($c) {
                $disp = new \Symfony\Component\EventDispatcher\EventDispatcher();
                $disp->addSubscriber($c['mailer']);
                $disp->addSubscriber($c['logger']);
                return $disp;
            }
        );

        $this['mailer'] = $this->share(
            function ($c) {
                return new MailSubscriber();
            }
        );

        $this['logger'] = $this->share(
            function ($c) {
                $monolog = new \Monolog\Logger('georg_channel');

                $dbHandler = new Log\DbHandler($c['pdo']);
                $formatter = new \Monolog\Formatter\LineFormatter('%message%');
                $dbHandler->setFormatter($formatter);
                $monolog->pushHandler($dbHandler);

                $monolog->pushHandler($c['logReturnHandler']);

                return new Log\LogSubscriber($monolog);
            }
        );

        $this['logReturnHandler'] = $this->share(
            function ($c) {
                return new Log\ReturnHandler;
            }
        );

        $this['organization'] = $this->share(
            function ($c) {
                return new \ledgr\autogiro\Builder\Organization(
                    Settings::getOrganisationName(),
                    Settings::getCorporateId(),
                    Settings::getAgCustomerNumber(),
                    Settings::getBankgiro()
                );
            }
        );

        $this['autogiroParser'] = $this->share(
            function ($c) {
                return new \ledgr\giro\Giro(new \ledgr\autogiro\AutogiroFactory);
            }
        );

        $this['autogiroBuilder'] = $this->share(
            function ($c) {
                return new \ledgr\autogiro\Builder\AutogiroBuilder($c['autogiroParser'], $c['organization']);
            }
        );

        $this['pdo'] = $this->share(
            function ($c) {
                $pdo = new \PDO('sqlite:georg.db');
                $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                return $pdo;
            }
        );

        $this['dbManager'] = $this->share(
            function ($c) {
                return new DbManager($c['pdo']);
            }
        );

        $this['filesystem'] = $this->share(
            function ($c) {
                return new \Gaufrette\Filesystem(
                    new \Gaufrette\Adapter\Local(sys_get_temp_dir())
                );
            }
        );

        // Setup paris
        \ORM::set_db($this['pdo']);
        \Model::$auto_prefix_models = '\\ledgr\\georg\\Model\\';
    }
}
