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

class StandardStoreHandler extends AbstractStoreHandler
{

    public function getOperationalMode()
    {
        return 'standard';
    }

    public function getZoneProducts(StoreZoneInterface $storeZone, array $context)
    {

        return $this->findProducts($context['taxonomyTerm']);
    }

    public function renderStoreZone(StoreZoneInterface $storeZone, $templating, array $context = array())
    {
        return $templating->renderResponse('VespolinaStoreBundle:Store:standard:zoneDetail.html.twig', $context);
    }
}
