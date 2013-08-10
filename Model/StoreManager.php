<?php
/**
 * (c) Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Vespolina\StoreBundle\Model;

use Symfony\Component\DependencyInjection\Container;

use Vespolina\StoreBundle\Model\StoreInterface;
use Vespolina\StoreBundle\Model\StoreManagerInterface;

/**
 * @author Daniel Kucharski <daniel@xerias.be>
 */
abstract class StoreManager implements StoreManagerInterface {

    protected $storeClass;
    protected $stores;
    protected $storesConfigurations;

    public function __construct($storeClass, $storesConfigurations) {

        $this->storeClass = $storeClass;
        $this->storesConfigurations = $storesConfigurations;

    }

    public function createStore($code, $name)
    {

        $baseClass = $this->storeClass;
        $store = new $baseClass;
        $store->setCode($code);
        $store->setName($name);

        return $store;
    }

    abstract function findStoreByCode($code);

    public function loadStoresConfigurations()
    {
        foreach ($this->storesConfigurations as $storeCode => $storeConfiguration) {

            $store = $this->createStore($storeCode, $storeConfiguration['display_name']);
            $store->getSettings()->setParameters($storeConfiguration);
            $this->stores[$storeCode] = $store;
        }

        return $this->stores;
    }
}
