<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\autogiro\toBank;

/**
 * Merge multiple containers
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class ContainerContainer implements ContainerInterface
{
    /**
     * @var ContainerInterface[] List of contained containers
     */
    private $containers = [];

    /**
     * Add container
     *
     * @param ContainerInterface $container
     */
    public function addContainer(ContainerInterface $container)
    {
        $this->containers[] = $container;
    }

    /**
     * Merge bank data from containers contained
     *
     * @return string ISO-8859-1 encoded string
     */
    public function createBankData()
    {
        return array_reduce(
            $this->containers,
            function ($carry, $container) {
                return $carry . $container->createBankData();
            },
            ''
        );
    }
}
