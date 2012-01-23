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
    protected $store;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {

        //Used for determining
        $host = $event->getRequest()->getHttpHost();
        //$storeManager = $this->container('vespolina.store_manager');
        //$this->store = $storeManager->findStoreByHost($host);


    }

}