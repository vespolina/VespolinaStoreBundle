<?php
/**
 * (c) Vespolina Project http://www.vespolina-project.org
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vespolina\StoreBundle\Event;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * @author Daniel Kucharski <daniel@xerias.be>
 */
class RequestListener
{
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {

        $storeResolver = $this->container->get('vespolina.store.store_resolver');
        $store = $storeResolver->resolveStore($event->getRequest());

        $storeZoneManager = $this->container->get('vespolina.store.store_zone_manager');
        $storeZones = $storeZoneManager->findBy(array());

        foreach($storeZones as $storeZone) {
            $store->addStoreZone($storeZone);
        }

        //Register store as a global Twig variable
        $this->container->get('twig')->addGlobal('store', $store);
    }

}