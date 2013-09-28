<?php

/**
 * (c) Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vespolina\StoreBundle\Model;

use Symfony\Component\DependencyInjection\Container;

/**
 * @author Daniel Kucharski <daniel@xerias.be>
 */
abstract class StoreZoneManager implements StoreZoneManagerInterface {

    protected $storeZoneClass;

    public function __construct($storeZoneClass)
    {
        $this->storeZoneClass = $storeZoneClass;
    }

    public function createStoreZone(StoreInterface $store, $name = 'default')
    {
        $baseClass = $this->storeZoneClass;
        $storeZone = new $baseClass;
        $storeZone->setStore($store, $name);

        return $storeZone;
    }

    abstract function findStoreZoneById($id);
}
