<?php

/**
 * (c) 2011 - ∞ Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Vespolina\StoreBundle\EventListener;

use Symfony\Component\DependencyInjection\Container;
use Vespolina\CartBundle\Event\CartPricingEvent;
use Vespolina\Entity\OrderInterface;

class CartPricingListener
{
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * This method is trigger when the cart is created and the pricing context is  initialized
     * This is the perfect place to inject contextual information required to determine cart prices
     *
     * For instance we inject the tax zone (automatically detected if possible) into the cart
     *
     * @param CartPricingEvent $event
     */
    public function onInitPricingContext(CartPricingEvent $event)
    {
        $pricingContext = $event->getPricingContext();
        $this->adjustTaxZone($pricingContext);
    }

    protected function adjustTaxZone($pricingContext)
    {
        $taxationManager = $this->container->get('vespolina.taxation_manager');
        $store = $this->container->get('vespolina.store.store_resolver')->getStore();

        //Basic tax zone lookup based on country and state
        $country = $store->getDefaultCountry();
        $state = $store->getDefaultState();

        if (null != $state) {
            $zoneCode = $country . '-' . $state;
        } else {
            $zoneCode = $country;
        }

        $taxZone = $taxationManager->findTaxZoneByCode($zoneCode);
        if (null != $taxZone) {
            $pricingContext->set('taxZone', $taxZone);
        }
    }
}