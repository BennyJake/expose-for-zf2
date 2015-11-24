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
            $expose_manager_handler = $sm->get('ExposeManagerHandler');
            $expose_manager_handler->init();
            $expose_manager_handler->custom();
            $expose_manager_handler->run();
            $expose_manager_handler->after();
            $expose_manager_handler->decide($e->getRouteMatch());
        });
        //$eventManager->attach('expose',MvcEvent::EVENT_ROUTE,array(new Expose(),'test'));
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
