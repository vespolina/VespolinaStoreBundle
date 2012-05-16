<?php

namespace Vespolina\StoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerAware;

class AbstractController extends ContainerAware
{
    protected $store;
    protected $storeHandler;

    protected function getStoreHandler()
    {
        if (!$this->storeHandler) {

            $operationalMode = $this->getStore()->getOperationalMode();

            if (!$operationalMode) {
                $operationalMode = 'standard';  //Always fall back to the standard handler
            }
            $this->storeHandler = $this->container->get('vespolina.store.handler.' . $operationalMode);
            $this->storeHandler->setStore($this->getStore());
        }

        return $this->storeHandler;
    }

    protected function getStore()
    {
        if (!$this->store) {

            $this-> store = $this->container->get('vespolina.store.store_resolver')->getStore();
        }

        return $this->store;
    }

    public function render($view, array $parameters = array(), Response $response = null)
    {
       return ($this->container->get('templating')->renderResponse($view, $parameters, $response));
    }

    protected function getEngine()
    {
        return 'twig';
    }
}
