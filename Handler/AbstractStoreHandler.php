<?php

/**
 * (c) Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vespolina\StoreBundle\Handler;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerAware;

use Vespolina\Product\Specification\ProductSpecification;
use Vespolina\StoreBundle\Model\StoreInterface;
use Vespolina\StoreBundle\Model\StoreZone;

abstract class AbstractStoreHandler extends ContainerAware
{
    protected $store;

    public function getStore()
    {
        return $this->store;
    }

    public function setStore(StoreInterface $store)
    {
        $this->store = $store;
    }

    public function resolveStoreZone(array $context) {

        $storeZone = new StoreZone();
        $storeZone->setTaxonomyName('products');

        return $storeZone;
    }

    protected function findProducts($taxonomyNodeSlug)
    {
        $productManager = $this->container->get('vespolina.product_manager');

        // Build the product specification object
        $specification = new ProductSpecification();

        // Add product categorisation as criteria if different from '_all'
        if (null !== $taxonomyNodeSlug && $taxonomyNodeSlug != '_all') {

            $specification->withTaxonomyNodeName($taxonomyNodeSlug);
        }

        return $productManager->findAll($specification);
    }
}
