<?php

/**
 * (c) 2011 - ∞ Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vespolina\StoreBundle\Resolver;

use Symfony\Component\HttpFoundation\Request;
use Vespolina\StoreBundle\Resolver\AbstractStoreResolver;

/**
 * @author Daniel Kucharski <daniel@xerias.be>
 */
class SingleStoreResolver extends AbstractStoreResolver
{

    /**
     * Resolve the default store in a single store environment
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Vespolina\StoreBundle\Model\StoreInterface
     * @throws \Exception
     */
    public function resolveStore(Request $request)
    {
        if (null == $this->store) {

            $this->store =  $this->storeManager->findStoreByCode('default_store');
            if (null == $this->store) {
                throw new \Exception('Could not resolve request to a store');
            }
        }

        return $this->store;
    }
}
