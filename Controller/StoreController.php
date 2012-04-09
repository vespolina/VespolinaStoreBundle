<?php

namespace Vespolina\StoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class StoreController extends Controller
{
    public function indexAction($taxonomyTerm)
    {

        return $this->render('VespolinaStoreBundle:Store:index.html.twig', array('taxonomyTerm' => $taxonomyTerm));
    }

    public function listAction($taxonomyTerm)
    {

        $products = $this->findProducts($taxonomyTerm);

        return $this->render('VespolinaStoreBundle:Store:list.html.twig', array('products' => $products));
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


}
