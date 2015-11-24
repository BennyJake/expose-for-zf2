<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ExposeForZf2\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $array = json_decode(file_get_contents('data/filter_rules.json'),true);
        return new ViewModel();
    }

    public function warnAction(){
        //var_dump('WANRED!');
        var_dump($this->params()->fromQuery());
        $expose = $this->getServiceLocator()->get('ExposeManagerHandler');
        var_dump(get_class($expose));
        $em = $expose->getExposeManager();
        var_dump($em->getImpact());
        return new ViewModel();
    }

    public function banAction(){
        var_dump('BANNED!');
        return new ViewModel();
    }
}
