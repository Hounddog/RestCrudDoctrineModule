<?php

namespace RestCrudDoctrineModule\Module;

use ZfcBase\Module\AbstractModule as ZfcBaseAbstractModule;

use Zend\ModuleManager\Feature;
use Zend\EventManager\EventInterface;
use Zend\Mvc\MvcEvent;


abstract class AbstractModule extends ZfcBaseAbstractModule
 implements Feature\BootstrapListenerInterface

{
	public function onBootstrap(EventInterface $e)
    {
        $app = $e->getApplication();
        $em  = $app->getEventManager()->getSharedManager();
        $sm  = $app->getServiceManager();

        $em->attach($this->getNamespace(), MvcEvent::EVENT_DISPATCH, function($e) use ($sm) {
            $strategy = $sm->get('ViewJsonStrategy');
            $view     = $sm->get('ViewManager')->getView();
            $strategy->attach($view->getEventManager());
        });
    }
}