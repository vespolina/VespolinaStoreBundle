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

    protected $stores;
    protected $storesConfigurations;

    public function __construct($storesConfigurations) {

        $this->storesConfigurations = $storesConfigurations;

    }

    public function createStore()
    {
        $cass = $this->getClass();
        $store = new $cass;

        return $store;
    }

    public function supportsClass($class)
    {
        return $class === $this->getClass();
    }


}
