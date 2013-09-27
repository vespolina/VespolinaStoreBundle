<?php

/**
 * (c) 2011 - ∞ Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vespolina\StoreBundle\Resolver;

use Symfony\Component\HttpFoundation\Request;
use Vespolina\StoreBundle\Model\StoreManagerInterface;
use Vespolina\StoreBundle\Resolver\StoreResolverInterface;

/**
 * @author Daniel Kucharski <daniel@xerias.be>
 */
abstract class AbstractStoreResolver implements StoreResolverInterface
{
    protected $storeManager;
    protected $store;

    public function __construct(StoreManagerInterface $storeManager)
    {
        $this->storeManager = $storeManager;
    }

    public function getStore()
    {
        return $this->store;
    }
}