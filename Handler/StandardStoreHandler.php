<?php
/**
 * (c) Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vespolina\StoreBundle\Handler;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Vespolina\StoreBundle\Handler\AbstractStoreHandler;
use Vespolina\StoreBundle\Model\StoreZoneInterface;

class StandardStoreHandler extends AbstractStoreHandler implements \Symfony\Component\DependencyInjection\ContainerAwareInterface
{

    public function getOperationalMode()
    {
        return 'standard';
    }

    public function getZoneProducts(StoreZoneInterface $storeZone, $query = true, array $context)
    {

        return $this->findProducts($context['taxonomyTerm']);
    }

    public function renderStoreZone(StoreZoneInterface $storeZone, $templating, array $context = array())
    {

        $context = array_merge(array('product_view' => $this->getStore()->getDefaultProductView()), $context);

        //Get products in this store zone as a doctrine query
        $productsQuery = $this->getZoneProducts($storeZone, true, $context);

        $context['productsPagination'] =  $this->container->get('knp_paginator')->paginate(
            $productsQuery,
            $this->container->get('request')->query->get('page', 1),
            10);


        return $templating->renderResponse('VespolinaStoreBundle:Store/standard:zoneDetail.html.twig', $context);
    }
}
