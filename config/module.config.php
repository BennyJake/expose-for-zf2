<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ExposeForZf2;

return array(
    'router' => array(
        'routes' => array(
            'warn' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'ExposeForZf2\Controller\Expose',
                        'action'     => 'warn',
                    ),
                ),
            ),
            'ban' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'ExposeForZf2\Controller\Expose',
                        'action'     => 'warn',
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'ExposeManagerHandler' => function (\Zend\ServiceManager\ServiceLocatorInterface $sl) {
                $config = $sl->get('Config');
                $exposeHandler = new $config['expose-for-zf2']['handler'];
                return $exposeHandler;
            },
            'ExposeManager' => function (\Zend\ServiceManager\ServiceLocatorInterface $sl) {

                $filters = $sl->get('loadExposeFilters');
                $filters = $filters instanceof \Expose\FilterCollection ? $filters : NULL;

                $logger = $sl->get('loadExposeLogger');
                $logger = in_array('Psr\Log\LoggerInterface',class_implements(get_class($logger))) ? $logger : NULL;

                $queue = $sl->get('loadExposeQueue');
                $queue = $queue instanceof \Expose\Queue ? $queue : NULL;

                return new \Expose\Manager($filters,$logger,$queue);
            },
            'loadExposeFilters' => function (\Zend\ServiceManager\ServiceLocatorInterface $sl) {
                $config = $sl->get('Config');
                $filter_func = $config['expose-for-zf2']['expose-filters'];
                return $filter_func($sl);
            },
            'loadExposeLogger' => function (\Zend\ServiceManager\ServiceLocatorInterface $sl) {
                $config = $sl->get('Config');
                $logger_func = $config['expose-for-zf2']['expose-logger'];
                return $logger_func instanceof \Closure ? $logger_func($sl) : new \stdClass();
            },
            'loadExposeQueue' => function (\Zend\ServiceManager\ServiceLocatorInterface $sl) {
                $config = $sl->get('Config');
                $queue_func = $config['expose-for-zf2']['expose-queue'];
                return $queue_func instanceof \Closure ? $queue_func($sl) : new \stdClass();
            },
        ),
    ),
    'controller_plugins' => array(
        'factories' => array(
            'exposeHandlerPlugin' => function (\Zend\Mvc\Controller\PluginManager $pluginManager) {
                return new \ExposeForZf2\Plugin\ExposeHandlerPlugin($pluginManager->getServiceLocator()->get('ExposeManager'),
                    $pluginManager->getServiceLocator()->get('Application')->getMvcEvent()->getRouteMatch());
            }
        )
    ),
);
