<?php

namespace Vespolina\StoreBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;

class StoreController extends ContainerAware
{

    protected $store;
    protected $activeStoreHandler;
    protected $storeHandlers;

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
    public function zoneDetailAction($taxonomyTerm)
    {

        $context = array('taxonomyTerm' => $taxonomyTerm);

        $storeHandler = $this->getStoreHandler();

        //Resolve the store zone using the request
        $storeZone = $storeHandler->resolveStoreZone($context);

        //Get a product pager for the products in this store zone
        $context['products'] = $storeHandler->getZoneProducts($storeZone, $context);

        return $storeHandler->renderStoreZone($storeZone, $this->container->get('templating'), $context);
    }


    public function addStoreHandler($storeHandler)
    {
        $this->storeHandlers[$storeHandler->getOperationalMode()] = $storeHandler;
    }

    protected function getEngine()
    {
        return 'twig';
    }

    protected function getStoreHandler()
    {
        if (!$this->activeStoreHandler) {

            $operationalMode = $this->getStore()->getOperationalMode();


            if (!$operationalMode) {
                $operationalMode = 'standard';  //Always fall back to the standard handler
            }
            $this->activeStoreHandler = $this->storeHandlers[$operationalMode];

            $this->activeStoreHandler->setStore($this->getStore());
        }

        return $this->activeStoreHandler;
    }

    protected function getStore()
    {
        if (!$this->store) {

            $this-> store = $this->container->get('vespolina.store_manager')->getCurrentStore();
        }

        return $this->store;
    }

    public function render($view, array $parameters = array(), Response $response = null)
    {
        return $this->container->get('templating')->renderResponse($view, $parameters, $response);
    }
}
