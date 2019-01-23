<?php namespace iio\inroute;
class Dependencies {
    private $container;
    public function __construct(\Pimple $container) {
        $this->container = $container;
    }
    public function iio_georg_Controller_QueueController(){
                $donorWorker = $this->container["donorWorker"];
                if (!$donorWorker instanceof \iio\georg\DonorWorker) {
                    throw new DependencyExpection("DI-container method 'donorWorker' must return a iio\georg\DonorWorker instance.");
                }
                $donorMapper = $this->container["donorMapper"];
                if (!$donorMapper instanceof \ORMWrapper) {
                    throw new DependencyExpection("DI-container method 'donorMapper' must return a ORMWrapper instance.");
                }
                $filesystem = $this->container["filesystem"];
                if (!$filesystem instanceof \Gaufrette\Filesystem) {
                    throw new DependencyExpection("DI-container method 'filesystem' must return a Gaufrette\Filesystem instance.");
                }
                $loghandler = $this->container["logReturnHandler"];
                if (!$loghandler instanceof \iio\georg\Log\ReturnHandler) {
                    throw new DependencyExpection("DI-container method 'logReturnHandler' must return a iio\georg\Log\ReturnHandler instance.");
                }

        return new \iio\georg\Controller\QueueController($donorWorker,$donorMapper,$filesystem,$loghandler);
    }
    public function iio_georg_Controller_DonorController(){

        return new \iio\georg\Controller\DonorController();
    }
}
function append_routes(\Aura\Router\Map $map, Dependencies $deps, CallerInterface $caller) {
    $map->add("QueueController::processQueue", "/processQueue", array(
        "method" => array(
            "GET",
        ),
        "values" => array(
            "controller" => function ($route) use ($map, $deps, $caller) {
                $cntrl = $deps->iio_georg_Controller_QueueController();
                return $caller->call(array($cntrl, "processQueue"), $route);
            }
        )
    ));
    $map->add("QueueController::billAll", "/billAll", array(
        "method" => array(
            "GET",
        ),
        "values" => array(
            "controller" => function ($route) use ($map, $deps, $caller) {
                $cntrl = $deps->iio_georg_Controller_QueueController();
                return $caller->call(array($cntrl, "billAll"), $route);
            }
        )
    ));
    $map->add("QueueController::downloadFile", "/files/{:fname}", array(
        "method" => array(
            "GET",
        ),
        "values" => array(
            "controller" => function ($route) use ($map, $deps, $caller) {
                $cntrl = $deps->iio_georg_Controller_QueueController();
                return $caller->call(array($cntrl, "downloadFile"), $route);
            }
        )
    ));
    $map->add("DonorController::addDonor", "/donors/", array(
        "method" => array(
            "POST",
        ),
        "values" => array(
            "controller" => function ($route) use ($map, $deps, $caller) {
                $cntrl = $deps->iio_georg_Controller_DonorController();
                return $caller->call(array($cntrl, "addDonor"), $route);
            }
        )
    ));
    $map->add("DonorController::readDonor", "/donors/{:donorId}", array(
        "method" => array(
            "GET",
        ),
        "values" => array(
            "controller" => function ($route) use ($map, $deps, $caller) {
                $cntrl = $deps->iio_georg_Controller_DonorController();
                return $caller->call(array($cntrl, "readDonor"), $route);
            }
        )
    ));
    $map->add("DonorController::readDonorList", "/donors", array(
        "method" => array(
            "GET",
        ),
        "values" => array(
            "controller" => function ($route) use ($map, $deps, $caller) {
                $cntrl = $deps->iio_georg_Controller_DonorController();
                return $caller->call(array($cntrl, "readDonorList"), $route);
            }
        )
    ));
    $map->add("DonorController::updateDonor", "/donors/{:donorId}", array(
        "method" => array(
            "PUT",
        ),
        "values" => array(
            "controller" => function ($route) use ($map, $deps, $caller) {
                $cntrl = $deps->iio_georg_Controller_DonorController();
                return $caller->call(array($cntrl, "updateDonor"), $route);
            }
        )
    ));
    $map->add("DonorController::deleteDonor", "/donors/{:donorId}", array(
        "method" => array(
            "DELETE",
        ),
        "values" => array(
            "controller" => function ($route) use ($map, $deps, $caller) {
                $cntrl = $deps->iio_georg_Controller_DonorController();
                return $caller->call(array($cntrl, "deleteDonor"), $route);
            }
        )
    ));
    return $map;
}
$container = new \iio\georg\Container;
$deps = new Dependencies($container);
$caller = new \iio\georg\Caller($container);
$map = new \Aura\Router\Map(new \Aura\Router\DefinitionFactory, new \Aura\Router\RouteFactory);
$map = append_routes($map, $deps, $caller);
return new Inroute($map);

