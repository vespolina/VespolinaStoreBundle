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

    function getClass();

    function createStore();
    function updateStore(StoreInterface $store);

    function findStoreByCode($code);
}
