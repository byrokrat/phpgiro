<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\georg\Setup;

use Composer\Script\Event;

/**
 * Georg install and update scripts
 * 
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class ComposerScripts
{
    /**
     * Create DbInstaller
     *
     * @param  Event       $event
     * @return DbInstaller
     */
    private static function setupDbInstaller(Event $event)
    {
        @include_once "vendor/autoload.php";
        @chdir('www');

        $container = new Container();

        // TODO Validera så att jag använder Container på rätt sätt, att @share respekteras
        // TODO Se till att det blir rätt rättigheter för georg.db

        return new DbInstaller(
            $event->getIO(),
            $container['dbManager']
        );
    }

    /**
     * Composer post install script
     *
     * @param  Event  $event
     * @return void
     */
    public static function postInstall(Event $event)
    {
        self::setupDbInstaller($event)->install();
    }

    /**
     * Composer post update script
     *
     * @param  Event  $event
     * @return void
     */
    public static function postUpdate(Event $event)
    {
        self::setupDbInstaller($event)->update();
    }
}
