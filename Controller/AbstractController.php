<?php

namespace Vespolina\StoreBundle\Controller;

use Vespolina\CommerceBundle\Controller\AbstractController as BaseAbstractController;

class AbstractController extends BaseAbstractController
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
            $this->storeHandler = $this->container->get('vespolina_store.handler.' . $operationalMode);
            $this->storeHandler->setStore($this->getStore());
        }

        return $this->storeHandler;
    }

    protected function getStore()
    {
        if (!$this->store) {

            $this-> store = $this->container->get('vespolina_store.store_resolver')->getStore();
        }

        return $this->store;
    }
}
