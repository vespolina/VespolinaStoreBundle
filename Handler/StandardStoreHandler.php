<?php

/**
 * (c) 2011 - ∞ Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vespolina\StoreBundle\Handler;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Vespolina\Entity\Pricing\Element\TotalDoughValueElement;
use Vespolina\StoreBundle\Model\StoreZoneInterface;

class StandardStoreHandler extends AbstractStoreHandler implements \Symfony\Component\DependencyInjection\ContainerAwareInterface
{
    public function getOperationalMode()
    {
        return 'standard';
    }

    public function getZoneProducts(StoreZoneInterface $storeZone, $query = true, array $context)
    {
        return $this->findProducts($context['taxonomyNodeSlug']);
    }

    public function renderStoreZone(StoreZoneInterface $storeZone, $templating, array $context = array())
    {

        $defaults = array('productView' => $this->getStore()->getSetting('default_product_view'),
                          'taxonomyName' => $storeZone->getTaxonomyName(),
                          'taxonomyRenderType' => 'BelowEachOther',
                          'productsPerPage' => 20);

        $context = array_merge($defaults, $context);

        $productsPager = $this->getZoneProducts($storeZone, true, $context);
        $context['productsPager'] = $productsPager;

        $pricingManager = $this->container->get('vespolina.pricing_manager');

        /**
        foreach($productsPager as $product) {

            $adjustedPricingSet = $pricingManager->process($product->getPricing());
            $test = $adjustedPricingSet['MSRPDiscountRate']->getAmount();

        } */
        /**
        $context['productsPagination'] =  $this->container->get('knp_paginator')->paginate(
        $productsQuery,
        $this->container->get('request')->query->get('page', 1),
        $context['productsPerPage']);
        $context['productsPagination']->setTemplate('VespolinaStoreBundle:Store/standard:pagination.html.twig');
         */

        return $templating->renderResponse('VespolinaStoreBundle:Store/standard:zoneDetail.html.twig', $context);
    }
}
