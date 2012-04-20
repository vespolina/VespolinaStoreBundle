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

use Vespolina\StoreBundle\Handler\StoreHandlerInterface;
use Vespolina\StoreBundle\Model\StoreZoneInterface;
use Vespolina\StoreBundle\Model\StoreZone;


abstract class AbstractStoreHandler extends ContainerAware implements StoreHandlerInterface
{

    public function resolveStoreZone(array $context) {

        $storeZone = new StoreZone();
        $storeZone->setTaxonomyName('product');

        return $storeZone;
    }

    public function renderStoreZone(StoreZoneInterface $storeZone, $templating, array $context = array())
    {
        return $templating->renderResponse('VespolinaStoreBundle:Store:zoneDetail.html.twig', $context);
    }

    protected function findProducts($taxonomyTerm)
    {
        $criteria = array();

        //Add product categorisation as criteria if different from 'all'
        if (null !== $taxonomyTerm && $taxonomyTerm != 'all') {
            $criteria['terms.slug'] = $taxonomyTerm;
        }

        $products = $this->container->get('vespolina.product_manager')->findBy($criteria);

        return $products;
    }


}
