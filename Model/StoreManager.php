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

    protected $currentStore;
    protected $storeClass;

    public function __construct($storeClass) {

        $this->storeClass = $storeClass;
    }

    public function createStore($id, $name)
    {

        $baseClass = $this->storeClass;
        $store = new $baseClass;
        $store->setId($id);
        $store->setName($name);
        return $store;
    }

    abstract function findStoreById($id);

    public function loadCurrentStore($host)
    {

        if (!$this->currentStore) {

            $multiStoreActive = false;

            if( !$multiStoreActive ) {

                $this->currentStore = $this->findStoreById('default_store');
            }
        }

        return $this->currentStore;
    }

    public function getCurrentStore()
    {
        return $this->currentStore;
    }


    public function setCurrentStore(StoreInterface $currentStore)
    {
        $this->currentStore = $currentStore;
    }
}
