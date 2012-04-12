<?php

namespace Vespolina\StoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class StoreController extends Controller
{

    protected $store;

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

        $products = $this->findProducts($taxonomyTerm);

        return $this->render('VespolinaStoreBundle:Store:zoneDetail.html.twig',
            array('products' => $products,
                  'taxonomyTerm' => $taxonomyTerm));
    }


    protected function findProducts($taxonomyTerm)
    {

        $criteria = array();

        //Add product categorisation as criteria if different from 'all'
        if (null !== $taxonomyTerm && $taxonomyTerm != 'all') {
            $criteria['terms.slug'] = $taxonomyTerm;
        }

        $products = $this->get('vespolina.product_manager')->findBy($criteria);

        return $products;
    }

    protected function getStore()
    {
        if (!$this->store) {

            $this-> store = $this->get('vespolina.store_manager')->getCurrentStore();
        }

        return $this->store;
    }

}
