<?php
/**
 * (c) Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Vespolina\StoreBundle\Resolver;

use \Symfony\Component\HttpFoundation\Request;
/**
 * Interface for resolving a store
 *
 * @author Daniel Kucharski <daniel@xerias.be>
 */
interface StoreResolverInterface
{

    /**
     * Get the resolved store
     *
     * @abstract
     * @return \Vespolina\StoreBundle\Model\StoreInterface
     */
    function getStore();

    /**
     * Resolve the store based on the current request
     *
     * @abstract
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Vespolina\StoreBundle\Model\StoreInterface
     */
    function resolveStore(Request $request);

}
