<?php
/**
 * Created by PhpStorm.
 * User: bench_000
 * Date: 11/29/2015
 * Time: 5:16 PM
 */

namespace ExposeForZf2\Plugin;

class ExposeHandlerPlugin extends \Zend\Mvc\Controller\Plugin\AbstractPlugin{

    private $exposeManager;
    private $originalRoute;

    public function __construct(\Expose\Manager $manager, \Zend\Mvc\Router\RouteMatch $route_match){
        $this->exposeManager = $manager;
        $this->originalRoute = $route_match;
    }

    public function  getExposeManager(){
        return $this->exposeManager;
    }

    public function getOriginalRoute(){
        return $this->originalRoute;
    }
}