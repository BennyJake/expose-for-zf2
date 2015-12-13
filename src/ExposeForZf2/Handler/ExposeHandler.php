<?php
/**
 * Created by PhpStorm.
 * User: bchrisman
 * Date: 11/19/2015
 * Time: 4:36 PM
 */

namespace ExposeForZf2\Handler;

use Expose\Manager;
use Zend\ServiceManager\ServiceLocatorInterface;

abstract class ExposeHandler implements \Zend\ServiceManager\ServiceLocatorAwareInterface{

    protected $serviceLocator;

    public function __construct(){}

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator){
        $this->serviceLocator = $serviceLocator;
    }

    public function getServiceLocator() {
        return $this->serviceLocator;
    }

    abstract protected function before(Manager $manager);

    public function run(Manager $manager){

        $data = array_merge($_POST,$_GET,$_COOKIE);
        $sendNotifications = $manager->getNotify() == NULL ? FALSE : TRUE;

        $queue = $this->getServiceLocator()->get('Config')['expose-for-zf2']['expose-queue'];
        $runQueue = (is_null($queue) || !$queue || empty($queue)) ? FALSE : TRUE;

        $manager->run($data,$sendNotifications,$runQueue);
    }

    abstract protected function after(Manager $manager);

    public function decide(\Expose\Manager $manager, \Zend\Mvc\Router\RouteMatch $routeMatch){

        $config = $this->serviceLocator->get('Config')['expose-for-zf2'];
        $impactLevel = $manager->getImpact();

        foreach($config['impact-actions']['global'] as $impactAction){
            if($impactLevel >= $impactAction['min'] &&
                $impactLevel <= $impactAction['max']){

                $routeMatch->setParam('controller',$impactAction['route']['controller']);
                $routeMatch->setParam('action',$impactAction['route']['action']);
                break;
            }
        }
    }
}