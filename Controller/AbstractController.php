<?php

/**
 * (c) 2011 - ∞ Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vespolina\StoreBundle\Controller;

use Vespolina\CommerceBundle\Controller\AbstractController as BaseAbstractController;

class AbstractController extends BaseAbstractController
{
    protected $store;
    protected $storeHandler;

    protected function getStoreHandler()
    {
        if (!$this->storeHandler) {

            $operationalMode = $this->getStore()->getSetting('operational_mode', 'standard');
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
