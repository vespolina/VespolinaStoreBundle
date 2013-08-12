<?php
/**
 * (c) Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Vespolina\StoreBundle\Model;

/**
 * @author Daniel Kucharski <daniel@xerias.be>
 */
interface StoreManagerInterface
{

    /**
     * Creates a store
     *
     * @param $code
     * @param $name
     *
     * @return StoreInterface
     */
    public function createStore($code, $name);

    /**
     * Updates a store, and optionally flush the persistence
     *
     * @param StoreInterface    $store      The store to update
     * @param bool              $andFlush   Also flush the persistence layer
     */
    public function updateStore(StoreInterface $store, $andFlush = true);
}
