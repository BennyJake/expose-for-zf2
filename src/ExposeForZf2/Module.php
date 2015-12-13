<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ExposeForZf2;

use ExposeForZf2\Test\ExposeChild;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        //$sharedEventManager = $eventManager->getSharedManager();
        $eventManager->attach(MvcEvent::EVENT_ROUTE, function ($e) {
            //var_dump($e->getName()); // returns MvcEvent::EVENT_ROUTE
            $sm = $e->getTarget()->getServiceManager();
            $exposeManager = $sm->get('ExposeManager');
            $exposeManagerHandler = $sm->get('ExposeManagerHandler');
            $exposeManagerHandler->before($exposeManager);
            $exposeManagerHandler->run($exposeManager);
            $exposeManagerHandler->after($exposeManager);
            $exposeManagerHandler->decide($exposeManager,$e->getRouteMatch());
        });
        //$eventManager->attach('expose',MvcEvent::EVENT_ROUTE,array(new Expose(),'test'));
    }



    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/../../src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
