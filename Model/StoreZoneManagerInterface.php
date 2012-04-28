<?php
/**
 * (c) Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Vespolina\StoreBundle\Model;

use Vespolina\StoreBundle\Model\StoreInterface;
use Vespolina\StoreBundle\Model\StoreZoneInterface;

/**
 * @author Daniel Kucharski <daniel@xerias.be>
 */
interface StoreZoneManagerInterface
{
    /**
     * Create a store zone
     *
     * @abstract
     *
     */
    function createStoreZone(StoreInterface $store, $name = 'default');

    function updateStoreZone(StoreZoneInterface $storeZone);


}
