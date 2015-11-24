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
    'service_manager' => array(
        'invokables' => array(
            'ExposeManagerHandler' => 'ExposeForZf2\ExposeChild'
        ),
        'factories' => array(
            'load_expose_filters' => function (\Zend\ServiceManager\ServiceLocatorInterface $sl) {
                $filter_collection = new \Expose\FilterCollection();
                $filter_collection->load();
                return $filter_collection;
            },
            'load_expose_logger' => function (\Zend\ServiceManager\ServiceLocatorInterface $sl) {
                $logger = new \Monolog\Logger('expose');
                return $logger;
            },
            //'load_expose_queue' => function (\Zend\ServiceManager\ServiceLocatorInterface $sl) {},
        ),
    ),
);
