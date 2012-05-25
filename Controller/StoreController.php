<?php

namespace Vespolina\StoreBundle\Controller;

use Vespolina\StoreBundle\Controller\AbstractController;

class StoreController extends AbstractController
{

    public function indexAction($taxonomyTerm)
    {
        return $this->render('VespolinaStoreBundle:Store:index.html.twig', array('taxonomyTerm' => $taxonomyTerm));
    }

    public function quickCustomerInspectionAction()
    {
        $customer = $this->container->get('session')->get('customer');

        return $this->render('VespolinaStoreBundle:Customer:quickInspection.html.twig', array('customer' => $customer));
    }

    /**
     * Displays a store zone which typically consists of a product list, taxonomy terms and some CMS content
     *
     * @param $taxonomyTerm
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function zoneDetailAction($taxonomyTerm)
    {
        $context = array('taxonomyTerm' => $taxonomyTerm);
        $storeHandler = $this->getStoreHandler();

        //Resolve the store zone using the request
        $storeZone = $storeHandler->resolveStoreZone($context);

        return $storeHandler->renderStoreZone($storeZone, $this->container->get('templating'), $context);
    }

}
