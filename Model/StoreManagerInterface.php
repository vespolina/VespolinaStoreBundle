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
     * Retrieve the current (active) store instance
     *
     * @abstract
     *
     */
     function getCurrentStore();


    /**
     * Set the current store
     *
     * @abstract
     * @param StoreInterface $store
     */
    function setCurrentStore(StoreInterface $store);

}
