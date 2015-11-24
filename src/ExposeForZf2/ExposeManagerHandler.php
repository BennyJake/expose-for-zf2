<?php
/**
 * Created by PhpStorm.
 * User: bchrisman
 * Date: 11/19/2015
 * Time: 4:36 PM
 */

namespace ExposeForZf2;

use Expose\Manager;
use Zend\ServiceManager\ServiceLocatorInterface;

abstract class ExposeManagerHandler implements \Zend\ServiceManager\ServiceLocatorAwareInterface{

    protected $serviceLocator;
    private $expose_manager;

    public function __construct(){}

    public function __invoke(){
        return $this->getExposeManager();
    }

    /**
     * @return mixed
     */
    public function getExposeManager()
    {
        return $this->expose_manager;
    }

    /**
     * @param mixed $expose_manager
     */
    public function setExposeManager($expose_manager)
    {
        $this->expose_manager = $expose_manager;
    }


    public function setServiceLocator(ServiceLocatorInterface $serviceLocator){
        $this->serviceLocator = $serviceLocator;
    }

    public function getServiceLocator() {
        return $this->serviceLocator;
    }

    public final function init(){

        if($this->serviceLocator->has('load_expose_filters')){
            $filters = $this->serviceLocator->get('load_expose_filters');
        }
        else{
            $filters = new \Expose\FilterCollection();
            $filters->load();
        }

        $logger = $this->serviceLocator->has('load_expose_filters') ? $this->serviceLocator->get('load_expose_logger') : NULL;

        $queue = $this->serviceLocator->has('load_expose_queue') ? $this->serviceLocator->get('load_expose_logger') : NULL;

        $this->expose_manager = new Manager($filters,$logger,$queue);
    }

    abstract protected function custom();

    public final function run(){

        $data = array_merge($_POST,$_GET,$_COOKIE);
        $this->expose_manager->run($data);
    }

    abstract protected function after();

    public final function decide(\Zend\Mvc\Router\RouteMatch $route_match){

        $config = $this->serviceLocator->get('Config')['expose'];
        $impact_level = $this->getExposeManager()->getImpact();

        foreach($config['impact_actions'] as $impact_actions){
            if($impact_level >= $impact_actions['min'] &&
                $impact_level <= $impact_actions['max']){

                $route_match->setParam('controller',$impact_actions['route']['controller']);
                $route_match->setParam('action',$impact_actions['route']['action']);
                break;
            }
        }

    }
}