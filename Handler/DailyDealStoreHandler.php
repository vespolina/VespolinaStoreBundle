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

class DailyDealStoreHandler extends AbstractStoreHandler
{

    public function getOperationalMode()
    {
        return 'dailyDeal';
    }

    public function getZoneProducts(StoreZoneInterface $storeZone, array $context)
    {
        $criteria = array();
        $products = $this->container->get('vespolina.product_manager')->findBy($criteria, null, 1);

        return $products;
    }

    public function renderStoreZone(StoreZoneInterface $storeZone, $templating, array $context = array())
    {
        //Todo: move responsability of filling the context to getZoneProducts?
        foreach ($context['products'] as $product) {
             $context['product'] = $product;
             continue;
        }

        return $templating->renderResponse('VespolinaStoreBundle:Store/dailyDeal:zoneDetail.html.twig', $context);
    }
}
