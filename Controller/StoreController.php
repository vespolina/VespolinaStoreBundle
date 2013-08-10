<?php

namespace Vespolina\StoreBundle\Controller;

use Vespolina\StoreBundle\Controller\AbstractController;

class StoreController extends AbstractController
{

    public function indexAction($taxonomyTerm)
    {
        return $this->render('VespolinaStoreBundle:Store:index.html.twig', array('taxonomyTerm' => $taxonomyTerm));
    }

    /**
     * Displays a store zone which typically consists of a product list, taxonomy terms and some CMS content
     *
     * @param $taxonomyTerm
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function zoneDetailAction($taxonomyNodeSlug)
    {
        $context = array('taxonomyNodeSlug' => $taxonomyNodeSlug);
        $storeHandler = $this->getStoreHandler();

        //Resolve the store zone using the request
        $storeZone = $storeHandler->resolveStoreZone($context);

        return $storeHandler->renderStoreZone($storeZone, $this->container->get('templating'), $context);
    }

}
