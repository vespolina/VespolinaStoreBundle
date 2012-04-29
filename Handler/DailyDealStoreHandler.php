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

    public function getZoneProducts(StoreZoneInterface $storeZone, $query = true, array $context)
    {

        $dm = $this->container->get('doctrine.odm.mongodb.default_document_manager');
        $productsQuery = $dm->createQueryBuilder('Application\Vespolina\ProductBundle\Document\Product')->getQuery();
        
        return $productsQuery->getSingleResult();
    }

    public function renderStoreZone(StoreZoneInterface $storeZone, $templating, array $context = array())
    {

        $context['product'] = $this->getZoneProducts($storeZone, false, $context);

        return $templating->renderResponse('VespolinaStoreBundle:Store/dailyDeal:zoneDetail.html.twig', $context);
    }
}
