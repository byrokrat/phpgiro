<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\georg\Controller;

use inroute\Route;
use ledgr\georg\DonorWorker;
use ledgr\georg\Exception\AutogiroException;
use ledgr\georg\Exception\RuntimeException;
use ledgr\georg\Log\ReturnHandler;
use ORMWrapper;
use Gaufrette\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Hal\Resource;
use Hal\Link;
use DateTime;

/**
 * Georg queue controller
 * 
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 * @controller
 */
class QueueController
{
    /**
     * @var DonorWorker Donor worker
     */
    private $donorWorker;

    /**
     * @var array List of all donors
     */
    private $donors;

    /**
     * @var Filesystem Filesystem abstraction
     */
    private $filesystem;

    /**
     * @var ReturnHandler Monolog handler
     */
    private $loghandler;

    /**
     * Georg queue controller
     *
     * @param DonorWorker   $donorWorker inject:donorWorker
     * @param ORMWrapper    $donorMapper inject:donorMapper
     * @param Filesystem    $filesystem  inject:filesystem
     * @param ReturnHandler $loghandler  inject:logReturnHandler
     */
    public function __construct(
        DonorWorker $donorWorker,
        ORMWrapper $donorMapper,
        Filesystem $filesystem,
        ReturnHandler $loghandler
    ) {
        $this->donorWorker = $donorWorker;
        $this->donors = $donorMapper->find_many();
        $this->filesystem = $filesystem;
        $this->loghandler = $loghandler;
    }

    /**
     * Process queue and generate AG-file
     *
     * @route  GET      </processQueue>  
     * @param  Route    $route
     * @return Response
     * @todo   processQueue: should route on POST
     */
    public function processQueue(Route $route)
    {
        $filename = '';

        try {
            $data = $this->donorWorker->process($this->donors);
            $filename = $this->fsWrite($data);
        } catch (AutogiroException $e) {
        }

        return new Response(
            $this->getHalContent($route->generate(), $filename),
            200,
            array('content-type' => 'application/json')
        );
    }

    /**
     * Bill all active donors
     *
     * @route  GET      </billAll>  
     * @param  Route    $route
     * @param  Request  $request
     * @return Response
     * @todo   billAll: should route on POST
     * @todo   billAll: write invoice date to log (actually done in worker)
     */
    public function billAll(Route $route, Request $request)
    {
        // TODO date should be validated more precise
        if (!$request->query->has('date')) {
            throw new RuntimeException('Bill date is required in query string');
        }

        $date = new DateTime($request->query->get('date'));
        $filename = '';

        try {
            $data = $this->donorWorker->billAll($this->donors, $date);
            $filename = $this->fsWrite($data);
        } catch (AutogiroException $e) {
        }

        return new Response(
            $this->getHalContent($route->generate(), $filename),
            200,
            array('content-type' => 'application/json')
        );
    }

    /**
     * Download generated AG-file
     *
     * @route  GET      </files/{:fname}>
     * @param  Route    $route
     * @return Response
     * @todo   Send 404 if file does not exist
     */
    public function downloadFile(Route $route)
    {
        $fname = $route->getValue('fname');
        
        $response = new Response();

        $response->headers->set(
            'Content-Disposition',
            $response->headers->makeDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                "$fname.txt"
            )
        );

        $response->setContent($this->filesystem->read($fname));

        return $response;
    }

    /**
     * Write data to filesystem
     *
     * @param  string $data
     * @return string The name of the file created
     */
    private function fsWrite($data)
    {
        $filename = implode('-', array('AG', date('Ymd'), mt_rand()));
        $this->filesystem->write($filename, $data);

        return $filename;
    }

    /**
     * Generate Hal resource
     *
     * @param  string   $urlSelf Url to self
     * @param  string   $urlRelated Url to related content
     * @return Resource Hal resource
     */
    private function getHalContent($urlSelf, $urlRelated = '')
    {
        $content = new Resource($urlSelf);
        if ($urlRelated) {
            $content->setLink(new Link("/files/$urlRelated", 'related'));
        }
        $content->setData('messages', $this->loghandler->getRecords());

        return $content;
    }
}
