<?php

namespace Vespolina\StoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class StoreController extends Controller
{
    public function indexAction()
    {
        return $this->render('VespolinaStoreBundle:Store:index.html.twig');
    }

    public function listAction()
    {
        $products = $this->get('vespolina.product_manager')->findBy(array());

        return $this->render('VespolinaStoreBundle:Store:list.html.twig', array('products' => $products));
    }
}
